<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 3.0.3 (doctrine2-annotation) on 2017-05-04 23:16:27.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Entity\ExerciseSchedule
 *
 * @Entity()
 * @Table(name="exercise_schedule", indexes={@Index(name="fk_exercise_history_exercise1_idx", columns={"exercise_id"}), @Index(name="fk_exercise_history_user1_idx", columns={"user_id"})}, uniqueConstraints={@UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 */
class ExerciseSchedule
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(name="`date`", type="date", nullable=true)
     */
    protected $date;

    /**
     * @Column(type="integer")
     */
    protected $exercise_id;

    /**
     * @Column(type="integer")
     */
    protected $user_id;

    /**
     * @OneToMany(targetEntity="Set", mappedBy="exerciseSchedule")
     * @JoinColumn(name="id", referencedColumnName="exercise_schedule_id", nullable=false)
     */
    protected $sets;

    /**
     * @ManyToOne(targetEntity="Exercise", inversedBy="exerciseSchedules")
     * @JoinColumn(name="exercise_id", referencedColumnName="id", nullable=false)
     */
    protected $exercise;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="exerciseSchedules")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    public function __construct()
    {
        $this->sets = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \Entity\ExerciseSchedule
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
     * Set the value of date.
     *
     * @param \DateTime $date
     * @return \Entity\ExerciseSchedule
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of exercise_id.
     *
     * @param integer $exercise_id
     * @return \Entity\ExerciseSchedule
     */
    public function setExerciseId($exercise_id)
    {
        $this->exercise_id = $exercise_id;

        return $this;
    }

    /**
     * Get the value of exercise_id.
     *
     * @return integer
     */
    public function getExerciseId()
    {
        return $this->exercise_id;
    }

    /**
     * Set the value of user_id.
     *
     * @param integer $user_id
     * @return \Entity\ExerciseSchedule
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of user_id.
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Add Set entity to collection (one to many).
     *
     * @param \Entity\Set $set
     * @return \Entity\ExerciseSchedule
     */
    public function addSet(Set $set)
    {
        $this->sets[] = $set;

        return $this;
    }

    /**
     * Remove Set entity from collection (one to many).
     *
     * @param \Entity\Set $set
     * @return \Entity\ExerciseSchedule
     */
    public function removeSet(Set $set)
    {
        $this->sets->removeElement($set);

        return $this;
    }

    /**
     * Get Set entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * Set Exercise entity (many to one).
     *
     * @param \Entity\Exercise $exercise
     * @return \Entity\ExerciseSchedule
     */
    public function setExercise(Exercise $exercise = null)
    {
        $this->exercise = $exercise;

        return $this;
    }

    /**
     * Get Exercise entity (many to one).
     *
     * @return \Entity\Exercise
     */
    public function getExercise()
    {
        return $this->exercise;
    }

    /**
     * Set User entity (many to one).
     *
     * @param \Entity\User $user
     * @return \Entity\ExerciseSchedule
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User entity (many to one).
     *
     * @return \Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function __sleep()
    {
        return array('id', 'date', 'exercise_id', 'user_id');
    }
}