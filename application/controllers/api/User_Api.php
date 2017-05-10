<?php

require FCPATH . 'application/libraries/REST_Controller.php';
require_once FCPATH . 'application/entity/autoload.php';

class User_Api extends REST_Controller
{
    /**
     * @var Doctrine
     */
    protected $dt;

    protected $em;

    public function __construct()
    {
        parent::__construct();

        $this->dt = $this->doctrine;
        $this->em = $this->dt->em;
    }

    public function test_get()
    {

    }

    public function index_get()
    {
        $id = $this->get('id');
        if (empty($id)) {
            $this->set_response(['message' => 'Specify user id or nickname'], REST_Controller::HTTP_UNAUTHORIZED); // OK (200) being the HTTP response code
        }
        /**
         * @var Entity\User $user
         */
        $user = $this->em->getRepository(USER)->find($id);

        $response = convertToResponseArray([$user]);
        $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function index_post()
    {
        $name = $this->post('name');
        $type = $this->post('type');

        $message = [
            'name' => $name,
            'type' => $type
        ];
        $exercise = new \Entity\Exercise();
        $exercise->setName($name);
        $exercise->setType($type);
        $this->em->persist($exercise);
        $this->em->flush();

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function workouts_get()
    {
        $id = $this->get('id');
        if (empty($id)) {
            $this->set_response(['message' => 'Specify user id or nickname'], REST_Controller::HTTP_UNAUTHORIZED);
        }

        $response = [];

        /**
         * @var Entity\User $user
         */
        $user = $this->em->getRepository(USER)->find($id);
        $workouts = $user->getWorkouts();

        /***
         * @var \Entity\Workout $workout
         */
        foreach ($workouts as $workout) {
            $exercises = $workout->getExercises();
//            \Doctrine\Common\Util\Debug::dump($exercises);
            array_push($response, [
                'id' => $workout->getId(),
                'name' => $workout->getName(),
                'exerciseList' => convertToResponseArray($exercises)
            ]);
        }

        $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }


    /***
     * Create new workout for current user and schedule it if Date is passed in
     */
    public function workouts_post()
    {
        $id = $this->get('id');
        if (empty($id)) {
            $this->set_response(['message' => 'Specify user id or nickname'], REST_Controller::HTTP_UNAUTHORIZED);
        }

        /**
         * @var Entity\User $user
         */
        $user = $this->em->getRepository(USER)->find($id);

        $name = $this->post('name');
        $date = $this->post('date');
        $isSuperset = $this->post('isSuperset');
        $date = new DateTime($date);
        $exerciseList = $this->post('exerciseList');

        $workout = new \Entity\Workout();
        $workout->setName($name);
        $workout->addUser($user);


        $workoutSchedule = new \Entity\WorkoutSchedule();
        $workoutSchedule->setDate($date);
        $workoutSchedule->setWorkout($workout);
        $workoutSchedule->setUser($user);

        foreach ($exerciseList as $exerciseRaw) {
            /**
             * @var Entity\Exercise $exercise
             */
            $exercise = $this->em->getRepository(EXERCISE)->find($exerciseRaw['id']);
            $workout->addExercise($exercise);
            $workoutScheduleExercise = new \Entity\WorkoutScheduleExercise();
            $workoutScheduleExercise->setWorkoutSchedule($workoutSchedule);
            $workoutScheduleExercise->setExercise($exercise);

            $this->em->persist($workoutScheduleExercise);
            $workoutSchedule->addWorkoutScheduleExercise($workoutScheduleExercise);
        }


        $workout->addWorkoutSchedule($workoutSchedule);

        $this->em->persist($workoutSchedule);
        $this->em->persist($workout);
        $this->em->flush();

        $this->set_response(['message' => 'Workout created', 'date' => $date], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function history_get()
    {
        $id = $this->get('id');
        if (empty($id)) {
            $this->set_response(['message' => 'Specify user id or nickname'], REST_Controller::HTTP_UNAUTHORIZED);
        }
        $response = [];

        /**
         * @var Entity\User $user
         */
        $user = $this->em->getRepository(USER)->find($id);
        $workouts = $user->getWorkouts();

        /***
         * @var \Entity\Workout $workout
         */
        foreach ($workouts as $workout) {
            $returnWorkout = [];
            $workout_schedules = $workout->getWorkoutSchedules();

            $returnWorkout['workout_id'] = $workout->getId();
            $returnWorkout['workout_name'] = $workout->getName();
            $returnWorkout['schedule'] = [];

            /***
             * @var \Entity\WorkoutSchedule $workout_schedule
             */
            foreach ($workout_schedules as $workout_schedule) {
                $returnSchedule = [];
                /***
                 * @var \Entity\WorkoutScheduleExercise[] $workout_schedule_exercises
                 */
                $workout_schedule_exercises = $workout_schedule->getWorkoutScheduleExercises();

                $returnSchedule['date'] = $workout_schedule->getDate()->format('d.m.Y');
                $returnSchedule['exerciseList'] = [];

                foreach ($workout_schedule_exercises as $workout_schedule_exercise) {
                    $returnScheduleExercise = getAllClassAttributes($workout_schedule_exercise->getExercise());
                    $returnScheduleExercise['setList'] = convertToResponseArray($workout_schedule_exercise->getSetScheduleds()->toArray());

                    array_push($returnSchedule['exerciseList'], $returnScheduleExercise);
                }
                array_push($returnWorkout['schedule'], $returnSchedule);
            }
            array_push($response, $returnWorkout);
        }
        $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}
