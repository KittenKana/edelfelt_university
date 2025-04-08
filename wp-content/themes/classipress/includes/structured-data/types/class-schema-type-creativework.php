<?php
/**
 * Schema.org structured data CreativeWork type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract CreativeWork schema type.
 *
 * @link  https://schema.org/CreativeWork
 *
 * @since 1.0.0
 *
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_CreativeWork extends APP_Schema_Type_Thing {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'CreativeWork';

	/**
	 * The subject matter of the content.
	 *
	 * @var APP_Schema_Type_Thing
	 */
	protected $about;

	/**
	 * The human sensory perceptual system or cognitive faculty through which a person may process or perceive information. Expected values include: auditory, tactile, textual, visual, colorDependent, chartOnVisual, chemOnVisual, diagramOnVisual, mathOnVisual, musicOnVisual, textOnVisual.
	 *
	 * @var APP_Schema_Property
	 */
	protected $accessMode;

	/**
	 * A list of single or combined accessModes that are sufficient to understand all the intellectual content of a resource. Expected values include: auditory, tactile, textual, visual.
	 *
	 * @var APP_Schema_Property
	 */
	protected $accessModeSufficient;

	/**
	 * Indicates that the resource is compatible with the referenced accessibility API (WebSchemas wiki lists possible values).
	 *
	 * @var APP_Schema_Property
	 */
	protected $accessibilityAPI;

	/**
	 * Identifies input methods that are sufficient to fully control the described resource (WebSchemas wiki lists possible values).
	 *
	 * @var APP_Schema_Property
	 */
	protected $accessibilityControl;

	/**
	 * Content features of the resource, such as accessible media, alternatives and supported enhancements for accessibility (WebSchemas wiki lists possible values).
	 *
	 * @var APP_Schema_Property
	 */
	protected $accessibilityFeature;

	/**
	 * A characteristic of the described resource that is physiologically dangerous to some users. Related to WCAG 2.0 guideline 2.3 (WebSchemas wiki lists possible values).
	 *
	 * @var APP_Schema_Property
	 */
	protected $accessibilityHazard;

	/**
	 * A human-readable summary of specific accessibility features or deficiencies, consistent with the other accessibility metadata but expressing subtleties such as 'short descriptions are present but long descriptions will be needed for non-visual users' or 'short descriptions are present and no long descriptions are needed.'
	 *
	 * @var APP_Schema_Property
	 */
	protected $accessibilitySummary;

	/**
	 * Specifies the Person that is legally accountable for the CreativeWork.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $accountablePerson;

	/**
	 * The overall rating, based on a collection of reviews or ratings, of the item.
	 *
	 * @var APP_Schema_Type_AggregateRating
	 */
	protected $aggregateRating;

	/**
	 * A secondary title of the CreativeWork.
	 *
	 * @var APP_Schema_Property
	 */
	protected $alternativeHeadline;

	/**
	 * A media object that encodes this CreativeWork. This property is a synonym for encoding.
	 *
	 * @var APP_Schema_Type_MediaObject
	 */
	protected $associatedMedia;

	/**
	 * An intended audience, i.e. a group for whom something was created. Supersedes serviceAudience.
	 *
	 * @var APP_Schema_Type_Audience
	 */
	protected $audience;

	/**
	 * An embedded audio object.
	 *
	 * @var APP_Schema_Type_AudioObject
	 */
	protected $audio;

	/**
	 * The author of this content or rating. Please note that author is special in that HTML 5 provides a special mechanism for indicating authorship via the rel tag. That is equivalent to this and may be used interchangeably.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $author;

	/**
	 * An award won by or for this item. Supersedes awards.
	 *
	 * @var APP_Schema_Property
	 */
	protected $award;

	/**
	 * Fictional person connected with a creative work.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $character;

	/**
	 * A citation or reference to another creative work, such as another publication, web page, scholarly article, etc.
	 *
	 * @var APP_Schema_Type_CreativeWork|APP_Schema_Property
	 */
	protected $citation;

	/**
	 * Comments, typically from users.
	 *
	 * @var APP_Schema_Type_Comment
	 */
	protected $comment;

	/**
	 * The number of comments this CreativeWork (e.g. Article, Question or Answer) has received. This is most applicable to works published in Web sites with commenting system; additional comments may exist elsewhere.
	 *
	 * @var APP_Schema_Property
	 */
	protected $commentCount;

	/**
	 * The location depicted or described in the content. For example, the location in a photograph or painting.
	 *
	 * @var APP_Schema_Type_Place
	 */
	protected $contentLocation;

	/**
	 * Official rating of a piece of content—for example,'MPAA PG-13'.
	 *
	 * @var APP_Schema_Property
	 */
	protected $contentRating;

	/**
	 * The specific time described by a creative work, for works (e.g. articles, video objects etc.) that emphasise a particular moment within an Event.
	 *
	 * @var APP_Schema_Property
	 */
	protected $contentReferenceTime;

	/**
	 * A secondary contributor to the CreativeWork or Event.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $contributor;

	/**
	 * The party holding the legal copyright to the CreativeWork.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $copyrightHolder;

	/**
	 * The year during which the claimed copyright for the CreativeWork was first asserted.
	 *
	 * @var APP_Schema_Property
	 */
	protected $copyrightYear;

	/**
	 * The creator/author of this CreativeWork. This is the same as the Author property for CreativeWork.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $creator;

	/**
	 * The date on which the CreativeWork was created or the item was added to a DataFeed.
	 *
	 * @var APP_Schema_Property
	 */
	protected $dateCreated;

	/**
	 * The date on which the CreativeWork was most recently modified or when the item's entry was modified within a DataFeed.
	 *
	 * @var APP_Schema_Property
	 */
	protected $dateModified;

	/**
	 * Date of first broadcast/publication.
	 *
	 * @var APP_Schema_Property
	 */
	protected $datePublished;

	/**
	 * A link to the page containing the comments of the CreativeWork.
	 *
	 * @var APP_Schema_Property
	 */
	protected $discussionUrl;

	/**
	 * Specifies the Person who edited the CreativeWork.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $editor;

	/**
	 * An alignment to an established educational framework.
	 *
	 * @var APP_Schema_Type_AlignmentObject
	 */
	protected $educationalAlignment;

	/**
	 * The purpose of a work in the context of education; for example, 'assignment', 'group work'.
	 *
	 * @var APP_Schema_Property
	 */
	protected $educationalUse;

	/**
	 * A media object that encodes this CreativeWork. This property is a synonym for associatedMedia. Supersedes encodings.
	 *
	 * @var APP_Schema_Type_MediaObject
	 */
	protected $encoding;

	/**
	 * A creative work that this work is an example/instance/realization/derivation of. Inverse property: workExample.
	 *
	 * @var APP_Schema_Type_CreativeWork
	 */
	protected $exampleOfWork;

	/**
	 * Date the content expires and is no longer useful or available. For example a VideoObject or NewsArticle whose availability or relevance is time-limited, or a ClaimReview fact check whose publisher wants to indicate that it may no longer be relevant (or helpful to highlight) after some date.
	 *
	 * @var APP_Schema_Property
	 */
	protected $expires;

	/**
	 * Media type, typically MIME format (see IANA site) of the content e.g. application/zip of a SoftwareApplication binary. In cases where a CreativeWork has several media type representations, 'encoding' can be used to indicate each MediaObject alongside particular fileFormat information. Unregistered or niche file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia entry.
	 *
	 * @var APP_Schema_Property
	 */
	protected $fileFormat;

	/**
	 * A person or organization that supports (sponsors) something through some kind of financial contribution.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $funder;

	/**
	 * Genre of the creative work, broadcast channel or group.
	 *
	 * @var APP_Schema_Property
	 */
	protected $genre;

	/**
	 * Indicates a CreativeWork that is (in some sense) a part of this CreativeWork. Inverse property: isPartOf.
	 *
	 * @var APP_Schema_Type_CreativeWork
	 */
	protected $hasPart;

	/**
	 * Headline of the article.
	 *
	 * @var APP_Schema_Property
	 */
	protected $headline;

	/**
	 * The language of the content or performance or used in an action. Please use one of the language codes from the IETF BCP 47 standard. See also availableLanguage. Supersedes language.
	 *
	 * @var APP_Schema_Property
	 */
	protected $inLanguage;

	/**
	 * The number of interactions for the CreativeWork using the WebSite or SoftwareApplication. The most specific child type of InteractionCounter should be used. Supersedes interactionCount.
	 *
	 * @var APP_Schema_Type_InteractionCounter
	 */
	protected $interactionStatistic;

	/**
	 * The predominant mode of learning supported by the learning resource. Acceptable values are 'active', 'expositive', or 'mixed'.
	 *
	 * @var APP_Schema_Property
	 */
	protected $interactivityType;

	/**
	 * A flag to signal that the item, event, or place is accessible for free. Supersedes free.
	 *
	 * @var APP_Schema_Property
	 */
	protected $isAccessibleForFree;

	/**
	 * A resource that was used in the creation of this resource. This term can be repeated for multiple sources. For example, http://example.com/great-multiplication-intro.html. Supersedes isBasedOnUrl.
	 *
	 * @var APP_Schema_Type_CreativeWork|APP_Schema_Type_Product|APP_Schema_Property
	 */
	protected $isBasedOn;

	/**
	 * Indicates whether this content is family friendly.
	 *
	 * @var APP_Schema_Property
	 */
	protected $isFamilyFriendly;

	/**
	 * Indicates a CreativeWork that this CreativeWork is (in some sense) part of. Inverse property: hasPart.
	 *
	 * @var APP_Schema_Type_CreativeWork
	 */
	protected $isPartOf;

	/**
	 * Keywords or tags used to describe this content. Multiple entries in a keywords list are typically delimited by commas.
	 *
	 * @var APP_Schema_Property
	 */
	protected $keywords;

	/**
	 * The predominant type or kind characterizing the learning resource. For example, 'presentation', 'handout'.
	 *
	 * @var APP_Schema_Property
	 */
	protected $learningResourceType;

	/**
	 * A license document that applies to this content, typically indicated by URL.
	 *
	 * @var APP_Schema_Type_CreativeWork|APP_Schema_Property
	 */
	protected $license;

	/**
	 * The location where the CreativeWork was created, which may not be the same as the location depicted in the CreativeWork.
	 *
	 * @var APP_Schema_Type_Place
	 */
	protected $locationCreated;

	/**
	 * Indicates the primary entity described in some page or other CreativeWork. Inverse property: mainEntityOfPage.
	 *
	 * @var APP_Schema_Type_Thing
	 */
	protected $mainEntity;

	/**
	 * A material that something is made from, e.g. leather, wool, cotton, paper.
	 *
	 * @var APP_Schema_Type_Product|APP_Schema_Property
	 */
	protected $material;

	/**
	 * Indicates that the CreativeWork contains a reference to, but is not necessarily about a concept.
	 *
	 * @var APP_Schema_Type_Thing
	 */
	protected $mentions;

	/**
	 * An offer to provide this item—for example, an offer to sell a product, rent the DVD of a movie, perform a service, or give away tickets to an event.
	 *
	 * @var APP_Schema_Type_Offer
	 */
	protected $offers;

	/**
	 * The position of an item in a series or sequence of items.
	 *
	 * @var APP_Schema_Property
	 */
	protected $position;

	/**
	 * The person or organization who produced the work (e.g. music album, movie, tv/radio series etc.).
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $producer;

	/**
	 * The service provider, service operator, or service performer; the goods producer. Another party (a seller) may offer those services or goods on behalf of the provider. A provider may also serve as the seller. Supersedes carrier.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $provider;

	/**
	 * A publication event associated with the item.
	 *
	 * @var APP_Schema_Type_PublicationEvent
	 */
	protected $publication;

	/**
	 * The publisher of the creative work.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $publisher;

	/**
	 * The publishing division which published the comic.
	 *
	 * @var APP_Schema_Type_Organization
	 */
	protected $publisherImprint;

	/**
	 * The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork. While such policies are most typically expressed in natural language, sometimes related information (e.g. indicating a funder) can be expressed using schema.org terminology.
	 *
	 * @var APP_Schema_Type_CreativeWork|APP_Schema_Property
	 */
	protected $publishingPrinciples;

	/**
	 * The Event where the CreativeWork was recorded. The CreativeWork may capture all or part of the event. Inverse property: recordedIn.
	 *
	 * @var APP_Schema_Type_Event
	 */
	protected $recordedAt;

	/**
	 * The place and time the release was issued, expressed as a PublicationEvent.
	 *
	 * @var APP_Schema_Type_PublicationEvent
	 */
	protected $releasedEvent;

	/**
	 * A review of the item. Supersedes reviews.
	 *
	 * @var APP_Schema_Type_Review
	 */
	protected $review;

	/**
	 * Indicates (by URL or string) a particular version of a schema used in some CreativeWork. For example, a document could declare a schemaVersion using an URL such as http://schema.org/version/2.0/ if precise indication of schema version was required by some application.
	 *
	 * @var APP_Schema_Property
	 */
	protected $schemaVersion;

	/**
	 * The Organization on whose behalf the creator was working.
	 *
	 * @var APP_Schema_Type_Organization
	 */
	protected $sourceOrganization;

	/**
	 * The spatialCoverage of a CreativeWork indicates the place(s) which are the focus of the content. It is a subproperty of contentLocation intended primarily for more technical and detailed materials. For example with a Dataset, it indicates areas that the dataset describes: a dataset of New York weather would have spatialCoverage which was the place: the state of New York. Supersedes spatial.
	 *
	 * @var APP_Schema_Type_Place
	 */
	protected $spatialCoverage;

	/**
	 * A person or organization that supports a thing through a pledge, promise, or financial contribution. e.g. a sponsor of a Medical Study or a corporate sponsor of an event.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $sponsor;

	/**
	 * The temporalCoverage of a CreativeWork indicates the period that the content applies to, i.e. that it describes, either as a DateTime or as a textual string indicating a time period in ISO 8601 time interval format. In the case of a Dataset it will typically indicate the relevant time period in a precise notation (e.g. for a 2011 census dataset, the year 2011 would be written '2011/2012'). Other forms of content e.g. ScholarlyArticle, Book, TVSeries or TVEpisode may indicate their temporalCoverage in broader terms - textually or via well-known URL. Written works such as books may sometimes have precise temporal coverage too, e.g. a work set in 1939 - 1945 can be indicated in ISO 8601 interval format format via '1939/1945'. Supersedes datasetTimeInterval, temporal.
	 *
	 * @var APP_Schema_Property
	 */
	protected $temporalCoverage;

	/**
	 * The textual content of this CreativeWork.
	 *
	 * @var APP_Schema_Property
	 */
	protected $text;

	/**
	 * A thumbnail image relevant to the Thing.
	 *
	 * @var APP_Schema_Property
	 */
	protected $thumbnailUrl;

	/**
	 * Approximate or typical time it takes to work with or through this learning resource for the typical intended target audience, e.g. 'P30M', 'P1H25M'.
	 *
	 * @var APP_Schema_Property
	 */
	protected $timeRequired;

	/**
	 * The work that this work has been translated from. e.g. 物种起源 is a translationOf “On the Origin of Species” Inverse property: workTranslation.
	 *
	 * @var APP_Schema_Type_CreativeWork
	 */
	protected $translationOfWork;

	/**
	 * Organization or person who adapts a creative work to different languages, regional differences and technical requirements of a target market, or that translates during some event.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $translator;

	/**
	 * The typical expected age range, e.g. '7-9', '11-'.
	 *
	 * @var APP_Schema_Property
	 */
	protected $typicalAgeRange;

	/**
	 * The version of the CreativeWork embodied by a specified resource.
	 *
	 * @var APP_Schema_Property
	 */
	protected $version;

	/**
	 * An embedded video object.
	 *
	 * @var APP_Schema_Type_VideoObject
	 */
	protected $video;

	/**
	 * Example/instance/realization/derivation of the concept of this creative work. eg. The paperback edition, first edition, or eBook. Inverse property: exampleOfWork.
	 *
	 * @var APP_Schema_Type_CreativeWork
	 */
	protected $workExample;

	/**
	 * A work that is a translation of the content of this work. e.g. 西遊記 has an English workTranslation “Journey to the West”,a German workTranslation “Monkeys Pilgerfahrt” and a Vietnamese translation Tây du ký bình khảo. Inverse property: translationOfWork.
	 *
	 * @var APP_Schema_Type_CreativeWork
	 */
	protected $workTranslation;
}
