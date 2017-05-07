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
                'exercises' => convertToResponseArray($exercises)
            ]);
        }

        $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
                $returnSchedule['exercises'] = [];

                foreach ($workout_schedule_exercises as $workout_schedule_exercise) {
                    $returnScheduleExercise = getAllClassAttributes($workout_schedule_exercise->getExercise());
                    $returnScheduleExercise['sets'] = convertToResponseArray($workout_schedule_exercise->getSetScheduleds()->toArray());

                    array_push($returnSchedule['exercises'], $returnScheduleExercise);
                }
                array_push($returnWorkout['schedule'], $returnSchedule);
            }
            array_push($response, $returnWorkout);
        }
        $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}
