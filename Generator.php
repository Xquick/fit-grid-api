<?php
/**
 * Created by IntelliJ IDEA.
 * User: amrazek
 * Date: 04/05/17
 * Time: 23:03
 */

require_once 'application/entity/autoload.php';
require_once 'cli-config.php';

/**
 *  Fill DB with exercises
 */
class MyGenerator
{
    public $doctrine;
    public $em;

    function __construct()
    {
        $this->doctrine = new Doctrine();
        $this->em = $this->doctrine->em;

        date_default_timezone_set('Europe/Prague');
    }

    function generate()
    {
        $this->generateExercises();
        $this->generateWorkouts();
        $this->generateSchedule();
    }

    function generateExercises()
    {
        $chest = ['flat benchpress', 'flat dumbbell press', 'flat flyes', 'flat machine press', 'incline benchpress', 'incline dumbbells', 'incline flyes', 'incline machine press', 'peckdeck', 'cable flyes', 'pushups', 'cavalier crossover', 'pullover', 'dips'];
        $back = ['pullups', 'deadlift', 'barbell row', 'dumbbell row - single arm', 'dumbbell row - both arms', 'standing t-bar row', 'seated cabel row - wide grip', 'seated cabel row - close grip', 'cable pulldown - wide grip', 'cable pulldown - close grip', 'standing lat pushdown', 'cable crossover pulldown - romboids', 'reverse peckdeck'];
        $shoulders = ['lateral side raise', 'front dumbell raise', 'barbell seated shoulder press', 'military press', 'push-press', 'behind neck seated shoulder press', 'facepull', 'machine shoulder press', 'seated rear delt raise 45degree', 'reverse peckdeck', 'inclide bench dumbbell- laying on side rear delt', 'cable crossover'];
        $biceps = ['dumbbell curls', 'barbell curls', 'barbell 21', 'hammer curls', 'ez standing curls', 'nautilus', 'chinups', 'incline bench dumbbell curls', 'incline bench dumbbell curls with twist - lying on belly', 'cable crossover curls', 'cable curls with straight bar', 'reverse grip barbell curls'];
        $triceps = ['cable pulldown - clasic grip', 'cable pulldown - neutral grip', 'cable pulldown - reverse grip', 'dumbbell kickback', 'seated overhead dumbell extension - double hand', 'seated overhead dumbell extension - single hand', 'close grip benchpress', 'dips'];

        foreach ($chest as $exerciseName) {
            $exercise = new Entity\Exercise();
            $exercise->setName($exerciseName);
            $exercise->setType('chest');
            $this->em->persist($exercise);
        }

        foreach ($back as $exerciseName) {
            $exercise = new Entity\Exercise();
            $exercise->setName($exerciseName);
            $exercise->setType('back');
            $this->em->persist($exercise);
        }

        foreach ($shoulders as $exerciseName) {
            $exercise = new Entity\Exercise();
            $exercise->setName($exerciseName);
            $exercise->setType('shoulders');
            $this->em->persist($exercise);
        }

        foreach ($biceps as $exerciseName) {
            $exercise = new Entity\Exercise();
            $exercise->setName($exerciseName);
            $exercise->setType('biceps');
            $this->em->persist($exercise);
        }

        foreach ($triceps as $exerciseName) {
            $exercise = new Entity\Exercise();
            $exercise->setName($exerciseName);
            $exercise->setType('triceps');
            $this->em->persist($exercise);
        }

        $this->em->flush();

        echo 'generated exercises' . PHP_EOL;
    }

    function generateWorkouts()
    {
        $workout = new \Entity\Workout();
        $user = new \Entity\User();

        $user->setFirstName('Adam');
        $user->setLastName('Mrazek');
        $user->setNickname('adamsfit');

        /***
         * @var \Entity\Exercise $exercise
         */
        $exercise = $this->em->getRepository(EXERCISE)->findOneBy(array('name' => 'deadlift'));
        $workout->addExercise($exercise);

        $exercise = $this->em->getRepository(EXERCISE)->findOneBy(array('name' => 'chinups'));
        $workout->addExercise($exercise);
        $workout->setName('my first workout');
        $this->em->persist($workout);

        $user->addWorkout($workout);
        $workout->addUser($user);

        $this->em->persist($user);
        $this->em->flush();
    }


    function generateSchedule()
    {
        /***
         * @var \Entity\User $user
         */
        $user = $this->em->getRepository(USER)->findOneBy(array('nickname' => 'adamsfit'));
        $workoutCollection = $user->getWorkouts();
        /***
         * @var \Entity\Workout $workout
         */
        $workout = $workoutCollection->first();

        $exerciseList = $workout->getExercises();

        $workoutSchedule = new \Entity\WorkoutSchedule();
        $workoutSchedule->setWorkout($workout);
        $workoutSchedule->setUser($user);
        $workoutSchedule->setDate(new \DateTime('22-07-2017'));


        /***
         * @var \Entity\Exercise $exercise
         */
        foreach ($exerciseList as $exercise) {
            $workoutScheduleExercise = new \Entity\WorkoutScheduleExercise();
            $workoutScheduleExercise->setWorkoutSchedule($workoutSchedule);
            $workoutScheduleExercise->setExercise($exercise);

            $set = new \Entity\SetScheduled();
            $set->setWorkoutScheduleExercise($workoutScheduleExercise);
            $set->setSetNumber(1);
            $set->setRepCount(10);
            $set->setWeight(100);
            $this->em->persist($set);

            $set = new \Entity\SetScheduled();
            $set->setWorkoutScheduleExercise($workoutScheduleExercise);
            $set->setSetNumber(2);
            $set->setRepCount(8);
            $set->setWeight(80);
            $this->em->persist($set);
            $this->em->persist($workoutScheduleExercise);

        }
//        \Doctrine\Common\Util\Debug::dump($workoutScheduleExercise);

        $workoutSchedule->addWorkoutScheduleExercise($workoutScheduleExercise);

        $this->em->persist($workoutSchedule);
        $this->em->flush();

    }
}

function generate()
{
    $gen = new MyGenerator();
    $gen->generate();
}

call_user_func($argv[1]);