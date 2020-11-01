<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FlightController extends Controller
{
	const API_URL = 'http://prova.123milhas.net/api/flights';
	const INBOUND_FLIGHTS = 'inbound';
	const OUTBOUND_FLIGHTS = 'outbound';

    public function index()
    {
		$outboundFlights = $this->getFlights(self::OUTBOUND_FLIGHTS);
		$inboundFlights = $this->getFlights(self::INBOUND_FLIGHTS);

		$outboundFlightsPrepared = $this->getFlightsPreparedArray($outboundFlights);
		$inboundFlightsPrepared = $this->getFlightsPreparedArray($inboundFlights);

		$this->getFlightGrouping($outboundFlightsPrepared, $inboundFlightsPrepared);

    	// return $group;
	}
	
	private function getFlights($filter = null)
	{
		// Adicionar try catch, testando o retorno pra erro na url
		$query = $filter ? "?$filter=1" : '';

		$guzzleClient = new Client();
		
    	$response = $guzzleClient->get(self::API_URL . $query);
    	$statusCode = $response->getStatusCode();
		$flights = $response->getBody()->getContents();
		
		return json_decode($flights, true);
	}

	private function getFlightsPreparedArray($flights)
	{
		$fareGroup = array();

		foreach ($flights as $flight) {
			$fareGroup[$flight['fare']][$flight['price']][] = $flight;
		}

		return $fareGroup;
	}

	private function getFlightGrouping($outboundFlightsPrepared, $inboundFlightsPrepared)
	{
		$flightGrouping = array();

		foreach ($outboundFlightsPrepared as $fare => $outboundFlightsPrices) {
			$inboundFlightsPrices = $inboundFlightsPrepared[$fare];

			if (!empty($outboundFlightsPrices) && !empty($inboundFlightsPrices)) {
				$this->buildFlightGrouping($outboundFlightsPrices, $inboundFlightsPrices);
			}
		}
	}

	private function buildFlightGrouping($outboundFlights, $inboundFlights)
	{
		$outboundFlightsPrices = array_keys($outboundFlights);
		$inboundFlightsPrices = array_keys($inboundFlights);

		foreach ($outboundFlightsPrices as $outboundPrice) {
			foreach ($inboundFlightsPrices as $inboundPrice) {
				// Criar o grupo para cada combinação;
			}
		}
	}

	private function patternGroup()
	{
		return array(
			"uniqueId" => 0,
			"totalPrice" => 0,
			"outBound" => array(),
			"inbound" => array()
		);
	}
}
