<?php

require FCPATH . 'application/libraries/REST_Controller.php';
require_once FCPATH . 'application/entity/Workout.php';
require_once FCPATH . 'application/entity/Exercise.php';

class Workouts extends REST_Controller
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
        $this->methods['workouts_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['workouts_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['workouts_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function test_get()
    {

    }

    public function workouts_get()
    {
        $id = $this->get('id');

        /**
         * @var Entity\Workout $workout
         */
        $workout = $this->em->getRepository('Entity\Exercise')->find($id);

        $response = convertToResponseArray($workout, $workout->__sleep());
        $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function workouts_post()
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

}