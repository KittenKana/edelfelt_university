<?php

/**
 * Schema.org structured data Action type class.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Abstract Action schema type.
 *
 * @link https://schema.org/Action
 * @link https://developers.google.com/gmail/markup/reference/types/Action
 *
 * @since 2.0.0
 */
abstract class APP_Schema_Type_Action extends APP_Schema_Type_Thing {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'Action';

	/**
	 * Indicates the current disposition of the Action.
	 *
	 * @var APP_Schema_Type_ActionStatusType
	 */
	protected $actionStatus;

	/**
	 * The direct performer or driver of the action (animate or inanimate).
	 * e.g. John wrote a book.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $agent;

	/**
	 * The endTime of something. For a reserved event or service
	 * (e.g. FoodEstablishmentReservation), the time that it is expected to end.
	 * For actions that span a period of time, when the action was performed.
	 * e.g. John wrote a book from January to December.
	 *
	 * Note that Event uses startDate/endDate instead of startTime/endTime, even
	 * when describing dates with times. This situation may be clarified in
	 * future revisions.
	 *
	 * @var APP_Schema_Type_DateTime
	 */
	protected $endTime;

	/**
	 * For failed actions, more information on the cause of the failure.
	 *
	 * @var APP_Schema_Type_Thing
	 */
	protected $error;

	/**
	 * The object that helped the agent perform the action. e.g. John wrote a
	 * book with a pen.
	 *
	 * @var APP_Schema_Type_Thing
	 */
	protected $instrument;

	/**
	 * The location of for example where the event is happening, an organization
	 * is located, or where an action takes place.
	 *
	 * @var APP_Schema_Type_PostalAddress|APP_Schema_Property
	 */
	protected $location;

	/**
	 * The object upon which the action is carried out, whose state is kept
	 * intact or changed. Also known as the semantic roles patient, affected or
	 * undergoer (which change their state) or theme (which doesn't).
	 * e.g. John read a book.
	 *
	 * @var APP_Schema_Type_Thing
	 */
	protected $object;

	/**
	 * Other co-agents that participated in the action indirectly.
	 * e.g. John wrote a book with Steve.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $participant;

	/**
	 * The result produced in the action. e.g. John wrote a book.
	 *
	 * @var APP_Schema_Type_Thing
	 */
	protected $result;

	/**
	 * The startTime of something. For a reserved event or service
	 * (e.g. FoodEstablishmentReservation), the time that it is expected to
	 * start. For actions that span a period of time, when the action was
	 * performed. e.g. John wrote a book from January to December.
	 *
	 * Note that Event uses startDate/endDate instead of startTime/endTime, even
	 * when describing dates with times. This situation may be clarified in
	 * future revisions.
	 *
	 * @var APP_Schema_Type_DateTime
	 */
	protected $startTime;

	/**
	 * Indicates a target EntryPoint for an Action.
	 *
	 * @var APP_Schema_Type_EntryPoint
	 */
	protected $target;

}
