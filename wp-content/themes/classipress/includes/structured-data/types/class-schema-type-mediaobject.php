<?php
/**
 * Schema.org structured data MediaObject type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Abstract MediaObject schema type.
 *
 * A media object, such as an image, video, or audio object embedded in a web
 * page or a downloadable dataset i.e. DataDownload. Note that a creative work
 * may have many media objects associated with it on the same web page. For
 * example, a page about a single song (MusicRecording) may have a music video
 * (VideoObject), and a high and low bandwidth audio stream (2 AudioObject's).
 *
 * @since 2.0.0
 */
abstract class APP_Schema_Type_MediaObject extends APP_Schema_Type_CreativeWork {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'MediaObject';

	/**
	 * A NewsArticle associated with the Media Object.
	 *
	 * @var APP_Schema_Type_NewsArticle
	 */
	protected $associatedArticle;

	/**
	 * The bitrate of the media object.
	 *
	 * @var APP_Schema_Property
	 */
	protected $bitrate;

	/**
	 * File size in (mega/kilo) bytes.
	 *
	 * @var APP_Schema_Property
	 */
	protected $contentSize;

	/**
	 * Actual bytes of the media object, for example the image file or video
	 * file.
	 *
	 * @var APP_Schema_Property
	 */
	protected $contentUrl;

	/**
	 * The duration of the item (movie, audio recording, event, etc.) in
	 * ISO 8601 date format.
	 *
	 * @var APP_Schema_Type_Duration
	 */
	protected $duration;

	/**
	 * A URL pointing to a player for a specific video. In general, this is the
	 * information in the src element of an embed tag and should not be the same
	 * as the content of the loc tag.
	 *
	 * @var APP_Schema_Property
	 */
	protected $embedUrl;

	/**
	 * The CreativeWork encoded by this media object.
	 *
	 * @var APP_Schema_Type_CreativeWork
	 */
	protected $encodesCreativeWork;

	/**
	 * mp3, mpeg4, etc.
	 *
	 * @var APP_Schema_Property
	 */
	protected $encodingFormat;

	/**
	 * The height of the item.
	 *
	 * @var APP_Schema_Property
	 */
	protected $height;

	/**
	 * Player type required—for example, Flash or Silverlight.
	 *
	 * @var APP_Schema_Property
	 */
	protected $playerType;

	/**
	 * The production company or studio responsible for the item e.g. series,
	 * video game, episode etc.
	 *
	 * @var APP_Schema_Type_Organization
	 */
	protected $productionCompany;

	/**
	 * The regions where the media is allowed. If not specified, then it's
	 * assumed to be allowed everywhere. Specify the countries in ISO 3166
	 * format.
	 *
	 * @var APP_Schema_Type_Place
	 */
	protected $regionsAllowed;

	/**
	 * Indicates if use of the media require a subscription (either paid or free
	 * ). Allowed values are true or false (note that an earlier version had
	 * 'yes', 'no').
	 *
	 * @var APP_Schema_Property
	 */
	protected $requiresSubscription;

	/**
	 * Date when this media object was uploaded to this site.
	 *
	 * @var APP_Schema_Property
	 */
	protected $uploadDate;

	/**
	 * The width of the item.
	 *
	 * @var APP_Schema_Property
	 */
	protected $width;

}
