<?php
/**
 * Schema.org structured data buildable type interface.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Defines common interface for form composites and leafs
 *
 * Implements "Composite" OOP design pattern.
 */
interface APP_Schema_Type_Buildable {
	/**
	 * Build type.
	 */
	public function build();
}
