<?php

namespace Nexcess\MAPPS\Integrations\PerformanceMonitor\Legacy\Factory;

use Nexcess\MAPPS\Integrations\PerformanceMonitor\Legacy\CustomPostTypes;
use Nexcess\MAPPS\Integrations\PerformanceMonitor\Legacy\Model\BaseModel;
use Nexcess\MAPPS\Integrations\PerformanceMonitor\Legacy\Model\Report;

/**
 * The `ReportFactory` instantiates and returns a `Report` model.
 */
class ReportFactory extends BaseFactory {

	/**
	 * The `Report` post type.
	 *
	 * @var string
	 */
	protected $postType = CustomPostTypes::POST_TYPES['report'];

	/**
	 * Creates a post in WordPress database containing the provided
	 * meta data and returns an `Report` model instance that uses
	 * the newly created post as an underlying data source.
	 *
	 * @param Array<mixed> $post_meta Post metadata to create the SiteChange from.
	 * @param BaseModel    $base      A `Model` object this `Report` belongs to.
	 *
	 * @return Report `Report` instance.
	 */
	public function create( array $post_meta = [], BaseModel $base = null ) {
		$additional_post_meta = [];

		if ( $base instanceof BaseModel ) {
			$additional_post_meta['post_parent'] = $base->getAssociatedPostId();
		}
		$new_post_id = $this->createPost( $post_meta, $additional_post_meta );

		return new Report( $new_post_id );
	}

	/**
	 * Generates a `post_name` value based on the provided
	 * post meta values.
	 *
	 * @param Array<mixed> $post_meta Post meta data associated with the post.
	 *
	 * @return string Post name string.
	 */
	protected function getPostName( array $post_meta ) {
		$date = current_time( 'Y-m-d' );

		return sprintf( 'performance-report-%s', $date );
	}
}
