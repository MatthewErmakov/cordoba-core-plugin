<?php
namespace Tribe\Project\Post_Types\Ukulele;

use Tribe\Libs\Post_Type\Post_Object;
use Tribe\Project\Post_Types\BaseInstrument;

class Ukulele extends Post_Object {
	use BaseInstrument;

	const NAME = 'ukulele';

	const LABEL_LOOP_INCLUDE_NEW = 'New';
	const LABEL_LOOP_INCLUDE_POPULAR = 'Most Popular';
	const LABEL_LOOP_INCLUDE_UKE_PACKS = 'Ukulele Packs';
	const LABEL_LOOP_INCLUDE_LIMITED = 'Limited Edition';

	public function __construct() {
		parent::__construct();

		$this->hooks();
	}
}
