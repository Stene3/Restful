<?php
namespace Drahak\Restful\Mapping;

use Drahak\Restful\InvalidArgumentException;
use Nette\Object;
use Nette\Utils\Strings;

/**
 * DataUrlMapper - encode or decode base64 file
 * @package Drahak\Restful\Mapping
 * @author Drahomír Hanák
 */
class DataUrlMapper extends Object implements IMapper
{

	/**
	 * Create DATA URL from file path
	 * @param array $data (mimeType => string, src => string)
	 * @return string
	 *
	 * @throws MappingException
	 * @throws InvalidArgumentException
	 */
	public function parseResponse($data)
	{
		if (!isset($data['src']) || !isset($data['mimeType'])) {
			throw new InvalidArgumentException('DataUrlMapper expects array(src => \'\', mimeType => \'\')');
		}
		$src = base64_encode($data['src']);
		return 'data:' . $data['mimeType'] . ';base64,'. $src;
	}

	/**
	 * Convert client request data to array or traversable
	 * @param mixed $data
	 * @return array (mimeType => string|null, src => string)
	 *
	 * @throws MappingException
	 */
	public function parseRequest($data)
	{
		$matches = Strings::match($data, "@^data:([\w/]+?);(\w+?),(.*)$@si");
		if (!$matches) {
			throw new MappingException('Given data URL is invalid.');
		}

		return array(
			'mimeType' => $matches[1],
			'src' => base64_decode($matches[3])
		);
	}


}