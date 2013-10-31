<?php
/**
 * Remove all generated cached images form the system.
 * e.g. result of SetHeight() or SetWidth()
 * {@link Image} object
 *
 * 
 * @author  Thierry Francois - @colymba - thierry@colymba.com
 * @copyright Copyright (c) 2013, Thierry Francois
 * 
 * @license http://opensource.org/licenses/BSD-3-Clause BSD Simplified
 *
 * @package silverstripe-utilities
 * @subpackage tasks
 */
class ClearAllGeneratedCachedImagesTask extends BuildTask
{
	
	protected $title = 'Clear All Generated Cached Images';	
	protected $description = 'Remove all generated cached images (result of image manipulation) form the system.';
	
	/**
	 * Check that the user has appropriate permissions to execute this task
	 */
	public function init()
	{
		if( !Director::is_cli() && !Director::isDev() && !Permission::check('ADMIN') )
		{
			return Security::permissionFailure();
		}
		
		parent::init();
	}
	
	/**
	 * Actually clear all the images
	 */
	public function run($request)
	{		
		$images = DataObject::get('Image');
		$total = $images->count();
		$done = 0;
		$files = 0;
		
		if( $images )
		{
			foreach($images as $image)
			{
				$deleted = $image->deleteFormattedImages();
				$files += $deleted;
				$done++;

				echo "<br/>" . "$done of $total ($deleted files deleted).";
				flush();
				ob_flush();
			}
		}
		else{
			echo "Nothing to clear.";
		}
		
		echo "<br/><br/>" . "Done. (Processed $done images. Deleted $files files).";
	}
	
}