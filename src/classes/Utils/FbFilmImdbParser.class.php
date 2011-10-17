<?php
/***************************************************************************
 *   Copyright (C) 2011 by Alexey Denisov                                  *
 *   alexeydsov@gmail.com                                                  *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

	class FbFilmImdbParserException extends BaseException {}

	/**
	 * Не реализован, т.к. не была побеждена русская кодировка на Imdb
	 */
	class FbFilmImdbParser
	{
		/**
		 * @var CurlHttpClient
		 */
		protected $client = null;

		/**
		 * @return FbFilmImdbParser
		 */
		public static function create()
		{
			return new self;
		}

		public function __construct()
		{
			$this->client = CurlHttpClient::create()->
				setFollowLocation(true);
		}

		/**
		 * @param integer $imdbFilmId
		 * @return FbFilm
		 */
		public function parseImdbId($imdbFilmId)
		{
			return $this->parseResponse(
				$this->sendRequest(
					$this->constructRequest($imdbFilmId)
				)
			);
		}

		protected function constructRequest($imdbFilmId)
		{
			$url = HttpUrl::create()->parse(FbFilm::makeImdbUrl($imdbFilmId));

			return HttpRequest::create()->
				setUrl($url)->
				setMethod(HttpMethod::get());
		}

		/**
		 * @return string
		 */
		protected function sendRequest(HttpRequest $request)
		{
			$url = $request->getUrl()->toString();
			$url = preg_replace('~[^a-z0-9]~i', '_', $url);
			$path = '/tmp/parseimdb/films/'.$url.'.data';

			if (file_exists($path) && is_readable($path)) {
				return file_get_contents($path);
			}

			$response = $this->client->send($request);
			$responseStatusId = $response->getStatus()->getId();
			if ($responseStatusId != HttpStatus::CODE_200) {
				throw new FbFilmImdbParserException('At response we get status '. $responseStatusId);
			}

			umask('0022');
			if (!file_exists(dirname($path))) {
				mkdir(dirname($path), '0777', true);
			}

			$responseBody = $response->getBody();
			file_put_contents($path, $responseBody);
			chmod($path, '0755');

			return $responseBody;
		}

		/**
		 * @param type $responseBody
		 * @return FbFilm
		 */
		protected function parseResponse($responseBody)
		{
			$filmName = $this->parseFilmName($responseBody);

			var_dump('final exit');
		}

		protected function parseFilmName($responseBody)
		{
			$pattern = '~<h1\s*class="header">(.*?)<~ius';
			if (preg_match($pattern, $responseBody, $match)) {
				$filmName = trim($match[1]);
				var_dump($filmName, html_entity_decode('&#428;'), html_entity_decode('&#428;', ENT_COMPAT, 'cp1251'));

				exit;

			}
		}
	}
?>