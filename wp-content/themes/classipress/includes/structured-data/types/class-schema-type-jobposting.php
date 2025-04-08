<?php

/**
 * Schema.org structured data JobPosting type class.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.1
 */

/**
 * Abstract JobPosting schema type.
 *
 * @link https://schema.org/JobPosting
 *
 * @since 2.0.1
 */
abstract class APP_Schema_Type_JobPosting extends APP_Schema_Type_Intangible {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'JobPosting';

	/**
	 * Publication date for the job posting.
	 *
	 * @var APP_Schema_Property
	 */
	protected $datePosted;

	/**
	 * Organization offering the job position.
	 *
	 * @var APP_Schema_Type_Organization
	 */
	protected $hiringOrganization;

	/**
	 * A (typically single) geographic location associated with the job position.
	 *
	 * @var APP_Schema_Type_Place
	 */
	protected $jobLocation;

	/**
	 * The title of the job.
	 *
	 * @var APP_Schema_Property
	 */
	protected $title;



	/**
	 * The base salary of the job or of an employee in an EmployeeRole.
	 *
	 * @var APP_Schema_Property
	 */
	protected $baseSalary;

	/**
	 * Type of employment (e.g. full-time, part-time, contract, temporary,
	 * seasonal, internship).
	 *
	 * @var APP_Schema_Property
	 */
	protected $employmentType;

	/**
	 * The date after when the item is not valid. For example the end of an
	 * offer, salary period, or a period of opening hours.
	 *
	 * @var APP_Schema_Property
	 */
	protected $validThrough;



	/**
	 * The location(s) applicants can apply from. This is usually used for
	 * telecommuting jobs where the applicant does not need to be in a physical
	 * office. Note: This should not be used for citizenship or work visa
	 * requirements.
	 *
	 * @var APP_Schema_Property
	 */
	protected $applicantLocationRequirements;

	/**
	 * Educational background needed for the position or Occupation.
	 *
	 * @var APP_Schema_Property
	 */
	protected $educationRequirements;

	/**
	 * An estimated salary for a job posting or occupation, based on a variety
	 * of variables including, but not limited to industry, job title, and
	 * location. Estimated salaries are often computed by outside organizations
	 * rather than the hiring organization, who may not have committed to the
	 * estimated value.
	 *
	 * @var APP_Schema_Property
	 */
	protected $estimatedSalary;

	/**
	 * Description of skills and experience needed for the position or
	 * Occupation.
	 *
	 * @var APP_Schema_Property
	 */
	protected $experienceRequirements;

	/**
	 * Description of bonus and commission compensation aspects of the job.
	 * Supersedes incentives.
	 *
	 * @var APP_Schema_Property
	 */
	protected $incentiveCompensation;

	/**
	 * The industry associated with the job position.
	 *
	 * @var APP_Schema_Property
	 */
	protected $industry;

	/**
	 * Description of benefits associated with the job. Supersedes benefits.
	 *
	 * @var APP_Schema_Property
	 */
	protected $jobBenefits;

	/**
	 * A description of the job location (e.g TELECOMMUTE for telecommute jobs).
	 *
	 * @var APP_Schema_Property
	 */
	protected $jobLocationType;

	/**
	 * A category describing the job, preferably using a term from a taxonomy
	 * such as BLS O*NET-SOC, ISCO-08 or similar, with the property repeated for
	 * each applicable value. Ideally the taxonomy should be identified, and
	 * both the textual label and formal code for the category should be
	 * provided.
	 *
	 * Note: for historical reasons, any textual label and formal code provided
	 * as a literal may be assumed to be from O*NET-SOC.
	 *
	 * @var APP_Schema_Property
	 */
	protected $occupationalCategory;

	/**
	 * Specific qualifications required for this role or Occupation.
	 *
	 * @var APP_Schema_Property
	 */
	protected $qualifications;

	/**
	 * The Occupation for the JobPosting.
	 *
	 * @var APP_Schema_Property
	 */
	protected $relevantOccupation;

	/**
	 * Responsibilities associated with this role or Occupation.
	 *
	 * @var APP_Schema_Property
	 */
	protected $responsibilities;

	/**
	 * The currency (coded using ISO 4217 ) used for the main salary information
	 * in this job posting or for this employee.
	 *
	 * @var APP_Schema_Property
	 */
	protected $salaryCurrency;

	/**
	 * Skills required to fulfill this role or in this Occupation.
	 *
	 * @var APP_Schema_Property
	 */
	protected $skills;

	/**
	 * Any special commitments associated with this job posting. Valid entries
	 * include VeteranCommit, MilitarySpouseCommit, etc.
	 *
	 * @var APP_Schema_Property
	 */
	protected $specialCommitments;

	/**
	 * The typical working hours for this job (e.g. 1st shift, night shift,
	 * 8am-5pm).
	 *
	 * @var APP_Schema_Property
	 */
	protected $workHours;
}
