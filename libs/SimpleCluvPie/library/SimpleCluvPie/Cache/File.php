<?php
/**
 * SimpleCluvPie
 *
 * A PHP-Based RSS and Atom Feed Framework.
 * Takes the hard work out of managing a complete RSS/Atom solution.
 *
 * Copyright (c) 2004-2016, Ryan Parman, Geoffrey Sneddon, Ryan McCue, and contributors
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * 	* Redistributions of source code must retain the above copyright notice, this list of
 * 	  conditions and the following disclaimer.
 *
 * 	* Redistributions in binary form must reproduce the above copyright notice, this list
 * 	  of conditions and the following disclaimer in the documentation and/or other materials
 * 	  provided with the distribution.
 *
 * 	* Neither the name of the SimpleCluvPie Team nor the names of its contributors may be used
 * 	  to endorse or promote products derived from this software without specific prior
 * 	  written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS
 * AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package SimpleCluvPie
 * @copyright 2004-2016 Ryan Parman, Geoffrey Sneddon, Ryan McCue
 * @author Ryan Parman
 * @author Geoffrey Sneddon
 * @author Ryan McCue
 * @link http://simplecluvpie.org/ SimpleCluvPie
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * Caches data to the filesystem
 *
 * @package SimpleCluvPie
 * @subpackage Caching
 */
class SimpleCluvPie_Cache_File implements SimpleCluvPie_Cache_Base
{
	/**
	 * Location string
	 *
	 * @see SimpleCluvPie::$cache_location
	 * @var string
	 */
	protected $location;

	/**
	 * Filename
	 *
	 * @var string
	 */
	protected $filename;

	/**
	 * File extension
	 *
	 * @var string
	 */
	protected $extension;

	/**
	 * File path
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Create a new cache object
	 *
	 * @param string $location Location string (from SimpleCluvPie::$cache_location)
	 * @param string $name Unique ID for the cache
	 * @param string $type Either TYPE_FEED for SimpleCluvPie data, or TYPE_IMAGE for image data
	 */
	public function __construct($location, $name, $type)
	{
		$this->location = $location;
		$this->filename = $name;
		$this->extension = $type;
		$this->name = "$this->location/$this->filename.$this->extension";
	}

	/**
	 * Save data to the cache
	 *
	 * @param array|SimpleCluvPie $data Data to store in the cache. If passed a SimpleCluvPie object, only cache the $data property
	 * @return bool Successfulness
	 */
	public function save($data)
	{
		if (file_exists($this->name) && is_writeable($this->name) || file_exists($this->location) && is_writeable($this->location))
		{
			if ($data instanceof SimpleCluvPie)
			{
				$data = $data->data;
			}

			$data = serialize($data);
			return (bool) file_put_contents($this->name, $data);
		}
		return false;
	}

	/**
	 * Retrieve the data saved to the cache
	 *
	 * @return array Data for SimpleCluvPie::$data
	 */
	public function load()
	{
		if (file_exists($this->name) && is_readable($this->name))
		{
			return unserialize(file_get_contents($this->name));
		}
		return false;
	}

	/**
	 * Retrieve the last modified time for the cache
	 *
	 * @return int Timestamp
	 */
	public function mtime()
	{
		return @filemtime($this->name);
	}

	/**
	 * Set the last modified time to the current time
	 *
	 * @return bool Success status
	 */
	public function touch()
	{
		return @touch($this->name);
	}

	/**
	 * Remove the cache
	 *
	 * @return bool Success status
	 */
	public function unlink()
	{
		if (file_exists($this->name))
		{
			return unlink($this->name);
		}
		return false;
	}
}
