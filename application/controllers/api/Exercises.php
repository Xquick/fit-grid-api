<?php

require FCPATH . 'application/libraries/REST_Controller.php';
require_once FCPATH . 'application/entity/autoload.php';

class Exercises extends REST_Controller
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

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['exercises_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['exercises_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['exercises_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function test_get()
    {

    }

    public function exercises_get()
    {
        $id = $this->get('id');

        /**
         * @var Entity\Exercise $exercise
         */
        $exercise = $this->em->getRepository('Entity\Exercise')->find($id);

        $response = convertToResponseArray($exercise, $exercise->__sleep());
        $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function exercises_post()
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

    public function generate_get()
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

        echo ':generated exercises... <br>';
    }
}