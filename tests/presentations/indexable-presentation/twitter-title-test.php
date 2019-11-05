<?php

namespace Yoast\WP\Free\Tests\Presentations\Indexable_Presentation;

use Mockery;
use Yoast\WP\Free\Helpers\Options_Helper;
use Yoast\WP\Free\Presentations\Indexable_Presentation;
use Yoast\WP\Free\Tests\Mocks\Indexable;
use Yoast\WP\Free\Tests\TestCase;

/**
 * Class Twitter_Title_Test
 *
 * @coversDefaultClass \Yoast\WP\Free\Presentations\Indexable_Presentation
 *
 * @group presentations
 * @group twitter
 * @group twitter-title
 */
class Twitter_Title_Test extends TestCase {

	use Presentation_Instance_Builder;

	/**
	 * @var Options_Helper|Mockery\MockInterface
	 */
	protected $option_helper;

	/**
	 * @var Indexable
	 */
	protected $indexable;

	/**
	 * @var Indexable_Presentation
	 */
	protected $instance;

	/**
	 * Does the setup for testing.
	 */
	public function setUp() {
		parent::setUp();

		$this->setInstance();
	}

	/**
	 * Tests the situation where the Twitter title is set.
	 *
	 * @covers ::generate_twitter_title
	 */
	public function test_generate_twitter_title_with_set_twitter_title() {
		$this->indexable->twitter_title = 'Twitter title';

		$this->assertEquals( 'Twitter title', $this->instance->generate_twitter_title() );
	}

	/**
	 * Tests the situation where no Twitter title is set, the OG title is set, and OG is enabled.
	 *
	 * @covers ::generate_twitter_title
	 */
	public function test_generate_twitter_title_with_set_og_title_and_og_enabled() {
		$this->context->open_graph_enabled = true;
		$this->indexable->og_title         = 'OG title';

		$this->assertEquals( 'OG title', $this->instance->generate_twitter_title() );
	}

	/**
	 * Tests the situation where no Twitter title is set, the OG title is set, and OG is disabled.
	 *
	 * @covers ::generate_twitter_title
	 */
	public function test_generate_twitter_title_with_set_og_title_and_og_disabled() {
		$this->context->open_graph_enabled = false;
		$this->indexable->title            = 'SEO title';

		$this->assertEquals( 'SEO title', $this->instance->generate_twitter_title() );
	}

	/**
	 * Tests the situation where no Twitter and OG titles are set, but the SEO title is set.
	 *
	 * @covers ::generate_twitter_title
	 */
	public function test_generate_twitter_title_with_set_seo_title() {
		$this->indexable->title = 'SEO title';

		$this->assertEquals( 'SEO title', $this->instance->generate_twitter_title() );
	}

	/**
	 * Tests the situation where an empty value is returned.
	 *
	 * @covers ::generate_twitter_title
	 */
	public function test_generate_twitter_title_with_empty_return_value() {
		$this->assertEmpty( $this->instance->generate_twitter_title() );
	}
}