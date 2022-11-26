<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\GroupInterface as GroupInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="fos_user")
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"user", "user-read"}},
 *     "denormalization_context"={"groups"={"user", "user-write"}},
 *     "filters"={"user.order_filter", "user.search_filter"}
 * })
 */
class User extends BaseUser implements ParticipantInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"user"})
     */
    protected $id;

    /**
     * @Groups({"user"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user", "thread-read"})
     */
    protected $fullname;

    /**
     * @Groups({"thread-read"})
     */
    private $isPatient;

    /**
     * @Groups({"user"})
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group", inversedBy="users")
     * @ORM\JoinTable(name="fos_user_group",
     *     joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"user"})
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"user"})
     */
    private $gender;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"user"})
     */
    private $phone;

    /**
     * @Groups({"user-write"})
     */
    protected $plainPassword;

    /**
     * @Groups({"user"})
     */
    protected $username;

    /**
     * @Groups({"user"})
     */
    protected $enabled;

    /**
     * @Groups({"user"})
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Recommendation", mappedBy="users")
     */
    private $recommendations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Visit", mappedBy="user")
     */
    protected $visits;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Training", mappedBy="user")
     */
    protected $trainings;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Measurement", mappedBy="user")
     */
    protected $measurements;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Diagnostic", mappedBy="user")
     */
    protected $diagnostics;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PsychoPhysicalDevelopment", mappedBy="user")
     */
    protected $psychoPhysicalDevelopments;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Consultation", mappedBy="user")
     */
    protected $consultations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="doctors")
     * @ApiProperty(attributes={"isReadableLink"=false})
     * @Groups({"user"})
     */
    private $patients;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="patients")
     * @ORM\JoinTable(name="doctor_patient",
     *     joinColumns={@ORM\JoinColumn(name="patient_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="doctor_id", referencedColumnName="id")}
     * )
     * @ApiProperty(attributes={"isReadableLink"=false})
     * @Groups({"user"})
     */
    private $doctors;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ImagingExamination", mappedBy="user")
     */
    protected $imagingExaminations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MedicalExamination", mappedBy="user")
     */
    protected $medicalExaminations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MedicalRecommendation", mappedBy="user")
     */
    protected $medicalRecommendations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DietRecommendation", mappedBy="user")
     */
    protected $dietRecommendations;

    /**
     * @Groups({"user"})
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Chat", mappedBy="users")
     */
    protected $chats;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->groups = new ArrayCollection();
        $this->patients = new ArrayCollection();
        $this->doctors = new ArrayCollection();
    }

    /**
     * @param string $fullname
     *
     * @return $this
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param \FOS\UserBundle\Model\UserInterface|null $user
     *
     * @return bool
     */
    public function isUser(UserInterface $user = null)
    {
        return $user instanceof self && $user->id === $this->id;
    }

    /**
     * Get vistis
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Add visit
     *
     * @param \AppBundle\Entity\Visit $visit
     *
     * @return User
     */
    public function addVisit(\AppBundle\Entity\Visit $visit)
    {
        $this->visits[] = $visit;

        return $this;
    }

    /**
     * Remove visit
     *
     * @param \AppBundle\Entity\Visit $visit
     */
    public function removeVisit(\AppBundle\Entity\Visit $visit)
    {
        $this->visits->removeElement($visit);
    }

    /**
     * Add consultation
     *
     * @param \AppBundle\Entity\Consultation $consultation
     *
     * @return User
     */
    public function addConsultation(\AppBundle\Entity\Consultation $consultation)
    {
        $this->consultations[] = $consultation;

        return $this;
    }

    /**
     * Remove consultation
     *
     * @param \AppBundle\Entity\Consultation $consultation
     */
    public function removeConsultation(\AppBundle\Entity\Consultation $consultation)
    {
        $this->consultations->removeElement($consultation);
    }

    /**
     * Get consultations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConsultations()
    {
        return $this->consultations;
    }

    /**
     * Add diagnostic
     *
     * @param \AppBundle\Entity\Diagnostic $diagnostic
     *
     * @return User
     */
    public function addDiagnostic(\AppBundle\Entity\Diagnostic $diagnostic)
    {
        $this->diagnostics[] = $diagnostic;

        return $this;
    }

    /**
     * Remove diagnostic
     *
     * @param \AppBundle\Entity\Diagnostic $diagnostic
     */
    public function removeDiagnostic(\AppBundle\Entity\Diagnostic $diagnostic)
    {
        $this->diagnostics->removeElement($diagnostic);
    }

    /**
     * Get diagnostic
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDiagnostics()
    {
        return $this->diagnostics;
    }

    /**
     * Add diagnostic
     *
     * @param \AppBundle\Entity\PsychoPhysicalDevelopment $psychoPhysicalDevelopment
     *
     * @return User
     */
    public function addPsychoPhysicalDevelopment(\AppBundle\Entity\PsychoPhysicalDevelopment $psychoPhysicalDevelopment)
    {
        $this->psychoPhysicalDevelopments[] = $psychoPhysicalDevelopment;

        return $this;
    }

    /**
     * Remove diagnostic
     *
     * @param \AppBundle\Entity\PsychoPhysicalDevelopment $psychoPhysicalDevelopment
     */
    public function removePsychoPhysicalDevelopment(\AppBundle\Entity\PsychoPhysicalDevelopment $psychoPhysicalDevelopment)
    {
        $this->psychoPhysicalDevelopments->removeElement($psychoPhysicalDevelopment);
    }

    /**
     * Get diagnostic
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPsychoPhysicalDevelopments()
    {
        return $this->psychoPhysicalDevelopments;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return User
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Add doctor
     *
     * @param \AppBundle\Entity\User $doctor
     *
     * @return User
     */
    public function addDoctor(\AppBundle\Entity\User $doctor)
    {
        $this->doctors[] = $doctor;

        return $this;
    }

    /**
     * Remove doctor
     *
     * @param \AppBundle\Entity\User $doctor
     */
    public function removeDoctor(\AppBundle\Entity\User $doctor)
    {
        $this->doctors->removeElement($doctor);
    }

    /**
     * Get doctors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDoctors()
    {
        return $this->doctors;
    }

    /**
     * Add patient
     *
     * @param \AppBundle\Entity\User $patient
     *
     * @return User
     */
    public function addPatient(\AppBundle\Entity\User $patient)
    {
        $this->patients[] = $patient;

        return $this;
    }

    /**
     * Remove patient
     *
     * @param \AppBundle\Entity\User $patient
     */
    public function removePatient(\AppBundle\Entity\User $patient)
    {
        $this->patients->removeElement($patient);
    }

    /**
     * Get patients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatients()
    {
        return $this->patients;
    }

    /**
     * @return mixed
     */
    public function getIsPatient()
    {
        return $this->isPatient = $this->doctors->count() > 0;
    }

    /**
     * Add imagingExamination.
     *
     * @param \AppBundle\Entity\ImagingExamination $imagingExamination
     *
     * @return User
     */
    public function addImagingExamination(\AppBundle\Entity\ImagingExamination $imagingExamination)
    {
        $this->imagingExaminations[] = $imagingExamination;

        return $this;
    }

    /**
     * Remove imagingExamination.
     *
     * @param \AppBundle\Entity\ImagingExamination $imagingExamination
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImagingExamination(\AppBundle\Entity\ImagingExamination $imagingExamination)
    {
        return $this->imagingExaminations->removeElement($imagingExamination);
    }

    /**
     * Get imagingExaminations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImagingExaminations()
    {
        return $this->imagingExaminations;
    }

    /**
     * Add group.
     *
     * @param GroupInterface $group
     *
     * @return Group
     */
    public function addGroup(GroupInterface $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group.
     *
     * @param \AppBundle\Entity\Group $group
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeGroups(\AppBundle\Entity\Group $group)
    {
        return $this->groups->removeElement($group);
    }

    /**
     * Get groups.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add training.
     *
     * @param \AppBundle\Entity\Training $training
     *
     * @return User
     */
    public function addTraining(\AppBundle\Entity\Training $training)
    {
        $this->trainings[] = $training;

        return $this;
    }

    /**
     * Remove training.
     *
     * @param \AppBundle\Entity\Training $training
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTraining(\AppBundle\Entity\Training $training)
    {
        return $this->trainings->removeElement($training);
    }

    /**
     * Get trainings.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrainings()
    {
        return $this->trainings;
    }

    /**
     * Add recommendation.
     *
     * @param \AppBundle\Entity\Recommendation $recommendation
     *
     * @return User
     */
    public function addRecommendation(\AppBundle\Entity\Recommendation $recommendation)
    {
        $this->recommendations[] = $recommendation;

        return $this;
    }

    /**
     * Remove recommendation.
     *
     * @param \AppBundle\Entity\Recommendation $recommendation
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRecommendation(\AppBundle\Entity\Recommendation $recommendation)
    {
        return $this->recommendations->removeElement($recommendation);
    }

    /**
     * Get recommendations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecommendations()
    {
        return $this->recommendations;
    }

    /**
     * Add measurement.
     *
     * @param \AppBundle\Entity\Measurement $measurement
     *
     * @return User
     */
    public function addMeasurement(\AppBundle\Entity\Measurement $measurement)
    {
        $this->measurements[] = $measurement;

        return $this;
    }

    /**
     * Remove measurement.
     *
     * @param \AppBundle\Entity\Measurement $measurement
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMeasurement(\AppBundle\Entity\Measurement $measurement)
    {
        return $this->measurements->removeElement($measurement);
    }

    /**
     * Get measurements.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMeasurements()
    {
        return $this->measurements;
    }

    /**
     * Add medicalExamination.
     *
     * @param \AppBundle\Entity\MedicalExamination $medicalExamination
     *
     * @return User
     */
    public function addMedicalExamination(\AppBundle\Entity\MedicalExamination $medicalExamination)
    {
        $this->medicalExaminations[] = $medicalExamination;

        return $this;
    }

    /**
     * Remove medicalExamination.
     *
     * @param \AppBundle\Entity\MedicalExamination $medicalExamination
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMedicalExamination(\AppBundle\Entity\MedicalExamination $medicalExamination)
    {
        return $this->medicalExaminations->removeElement($medicalExamination);
    }

    /**
     * Get medicalExaminations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedicalExaminations()
    {
        return $this->medicalExaminations;
    }

    /**
     * Add medicalRecommendation.
     *
     * @param \AppBundle\Entity\MedicalRecommendation $medicalRecommendation
     *
     * @return User
     */
    public function addMedicalRecommendation(\AppBundle\Entity\MedicalRecommendation $medicalRecommendation)
    {
        $this->medicalRecommendations[] = $medicalRecommendation;

        return $this;
    }

    /**
     * Remove medicalRecommendation.
     *
     * @param \AppBundle\Entity\MedicalRecommendation $medicalRecommendation
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMedicalRecommendation(\AppBundle\Entity\MedicalRecommendation $medicalRecommendation)
    {
        return $this->medicalRecommendations->removeElement($medicalRecommendation);
    }

    /**
     * Get medicalRecommendations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedicalRecommendations()
    {
        return $this->medicalRecommendations;
    }

    /**
     * Add dietRecommendation.
     *
     * @param \AppBundle\Entity\DietRecommendation $dietRecommendation
     *
     * @return User
     */
    public function addDietRecommendation(\AppBundle\Entity\DietRecommendation $dietRecommendation)
    {
        $this->dietRecommendations[] = $dietRecommendation;

        return $this;
    }

    /**
     * Remove dietRecommendation.
     *
     * @param \AppBundle\Entity\DietRecommendation $dietRecommendation
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDietRecommendation(\AppBundle\Entity\DietRecommendation $dietRecommendation)
    {
        return $this->dietRecommendations->removeElement($dietRecommendation);
    }

    /**
     * Get dietRecommendations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDietRecommendations()
    {
        return $this->dietRecommendations;
    }

    /**
     * Add chat.
     *
     * @param \AppBundle\Entity\Chat $chat
     *
     * @return User
     */
    public function addChat(\AppBundle\Entity\Chat $chat)
    {
        $this->chats[] = $chat;

        return $this;
    }

    /**
     * Remove chat.
     *
     * @param \AppBundle\Entity\Chat $chat
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChat(\AppBundle\Entity\Chat $chat)
    {
        return $this->chats->removeElement($chat);
    }

    /**
     * Get chats.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChats()
    {
        return $this->chats;
    }
}
