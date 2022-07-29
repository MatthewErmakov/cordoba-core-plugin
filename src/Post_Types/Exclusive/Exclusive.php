<?php
namespace Tribe\Project\Post_Types\Exclusive;

use Tribe\Libs\Post_Type\Post_Object;
use Tribe\Project\Post_Types\BaseInstrument;

class Exclusive extends Post_Object {

	use BaseInstrument;

	const NAME = 'exclusive';

	public function __construct() {
		parent::__construct();

		$this->hooks();
	}
}