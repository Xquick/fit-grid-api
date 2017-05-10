<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 3.0.3 (doctrine2-annotation) on 2017-05-10 21:13:21.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Entity\Exercise
 *
 * @Entity()
 * @Table(name="exercise", uniqueConstraints={@UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 */
class Exercise
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(name="`name`", type="string", length=100, nullable=true)
     */
    protected $name;

    /**
     * @Column(name="`type`", type="string", length=50, nullable=true)
     */
    protected $type;

    /**
     * @OneToMany(targetEntity="WorkoutScheduleExercise", mappedBy="exercise")
     * @JoinColumn(name="id", referencedColumnName="exercise_id", nullable=false)
     */
    protected $workoutScheduleExercises;

    /**
     * @ManyToMany(targetEntity="Workout", mappedBy="exercises")
     */
    protected $workouts;

    public function __construct()
    {
        $this->workoutScheduleExercises = new ArrayCollection();
        $this->workouts = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \Entity\Exercise
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of name.
     *
     * @param string $name
     * @return \Entity\Exercise
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of type.
     *
     * @param string $type
     * @return \Entity\Exercise
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add WorkoutScheduleExercise entity to collection (one to many).
     *
     * @param \Entity\WorkoutScheduleExercise $workoutScheduleExercise
     * @return \Entity\Exercise
     */
    public function addWorkoutScheduleExercise(WorkoutScheduleExercise $workoutScheduleExercise)
    {
        $this->workoutScheduleExercises[] = $workoutScheduleExercise;

        return $this;
    }

    /**
     * Remove WorkoutScheduleExercise entity from collection (one to many).
     *
     * @param \Entity\WorkoutScheduleExercise $workoutScheduleExercise
     * @return \Entity\Exercise
     */
    public function removeWorkoutScheduleExercise(WorkoutScheduleExercise $workoutScheduleExercise)
    {
        $this->workoutScheduleExercises->removeElement($workoutScheduleExercise);

        return $this;
    }

    /**
     * Get WorkoutScheduleExercise entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkoutScheduleExercises()
    {
        return $this->workoutScheduleExercises;
    }

    /**
     * Add Workout entity to collection.
     *
     * @param \Entity\Workout $workout
     * @return \Entity\Exercise
     */
    public function addWorkout(Workout $workout)
    {
        $this->workouts[] = $workout;

        return $this;
    }

    /**
     * Remove Workout entity from collection.
     *
     * @param \Entity\Workout $workout
     * @return \Entity\Exercise
     */
    public function removeWorkout(Workout $workout)
    {
        $this->workouts->removeElement($workout);

        return $this;
    }

    /**
     * Get Workout entity collection.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkouts()
    {
        return $this->workouts;
    }

    public function __sleep()
    {
        return array('id', 'name', 'type');
    }
}