<?php
namespace Tribe\Project\Post_Types\Guitar;

use Tribe\Libs\Post_Type\Post_Object;
use Tribe\Project\Post_Types\BaseInstrument;

class Guitar extends Post_Object {

	use BaseInstrument;

	const NAME = 'guitar';

	public function __construct() {
		parent::__construct();

		$this->hooks();
	}
}