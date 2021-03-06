<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class FlightController extends Controller
{
	const API_URL = 'http://prova.123milhas.net/api/flights';
	const INBOUND_FLIGHTS = 'inbound';
	const OUTBOUND_FLIGHTS = 'outbound';

    public function index()
    {
		try {
            /*
            / Foram realizadas duas requisições para consumir a API apenas para explorar mais o código.
            / Uma boa prática, neste caso, seria realizar apenas uma.
            */
			$outboundFlights = $this->getFlights(self::OUTBOUND_FLIGHTS);
			$inboundFlights = $this->getFlights(self::INBOUND_FLIGHTS);

			$flightGrouping = $this->getFlightGrouping($outboundFlights, $inboundFlights);

			return response()->json($flightGrouping);
		} catch(\Exception $e) {
			return response()->json([
				"error" => $e->getMessage()
			]);
		}
	}
	
	private function getFlights($filter = null)
	{
		try {
			$query = $filter ? "?$filter=1" : '';
			
			$guzzleClient = new Client();
			
			$response = $guzzleClient->get(self::API_URL . $query);
			$statusCode = $response->getStatusCode();
			if ($statusCode <> 200) {
				throw new \Exception();
			}

			$flights = $response->getBody()->getContents();

			return json_decode($flights, true);
		} catch(\Exception $e) {
			throw new \Exception("Não foi possível acessar a API para consultar os voos.");
		}
	}

	private function getFlightGrouping($outboundFlights, $inboundFlights)
	{
		if (empty($outboundFlights) || empty($inboundFlights)) {
			throw new \Exception("Não foram encontrados voos de ida e/ou volta.");
		}

		$flights = array_merge($outboundFlights, $inboundFlights);

		$outboundFlightsPrepared = $this->getFlightsPreparedArray($outboundFlights);
		$inboundFlightsPrepared = $this->getFlightsPreparedArray($inboundFlights);

		$grouping = $this->buildGrouping($outboundFlightsPrepared, $inboundFlightsPrepared);
		$flightGrouping = $this->buildFlightGrouping($flights, $grouping);

		return $flightGrouping;
	}

	private function getFlightsPreparedArray($flights)
	{
		$preparedArray = array();

		foreach ($flights as $flight) {
			$preparedArray[$flight['fare']][$flight['price']][] = array('id' => $flight['id']);
		}

		return $preparedArray;
	}

	private function buildGrouping($outboundFlightsPrepared, $inboundFlightsPrepared)
	{
		$grouping = array();
		$uniqueId = 0;

		foreach ($outboundFlightsPrepared as $fare => $outboundFlightsPrices) {
			$inboundFlightsPrices = $inboundFlightsPrepared[$fare];

			if (!empty($outboundFlightsPrices) && !empty($inboundFlightsPrices)) {
				$groupingFare = $this->buildGroupingFare($uniqueId, $outboundFlightsPrices, $inboundFlightsPrices);
				$grouping = array_merge($grouping, $groupingFare);
			}
		}

		if (empty($grouping)) {
			throw new \Exception("Não foram encontrados voos de ida e volta com a mesma tarifa.");
		}

		usort($grouping, function($groupA, $groupB) { return ($groupA['totalPrice'] <=> $groupB['totalPrice']); });		

		return $grouping;
	}

	private function buildGroupingFare(&$uniqueId, $outboundFlights, $inboundFlights)
	{
		$groupingFare = array();
		$outboundFlightsPrices = array_keys($outboundFlights);
		$inboundFlightsPrices = array_keys($inboundFlights);

		foreach ($outboundFlightsPrices as $outboundPrice) {
			foreach ($inboundFlightsPrices as $inboundPrice) {
				$grouping = $this->groupingPattern();

				$grouping['uniqueId'] = ++$uniqueId;
				$grouping['totalPrice'] = $outboundPrice + $inboundPrice;
				$grouping['outbound'] = $outboundFlights[$outboundPrice];
				$grouping['inbound'] = $inboundFlights[$inboundPrice];

				$groupingFare[] = $grouping;
			}
		}

		return $groupingFare;
	}

	private function groupingPattern()
	{
		return array(
			"uniqueId" => 0,
			"totalPrice" => 0,
			"outbound" => array(),
			"inbound" => array()
		);
	}

	private function buildFlightGrouping($flights, $grouping)
	{
		$flightGrouping = $this->flightGroupingPattern();

		$flightGrouping['flights'] = $flights;
		$flightGrouping['groups'] = $grouping;
		$flightGrouping['totalGroups'] = count($grouping);
		$flightGrouping['totalFlights'] = $this->getTotalUsedFlights($grouping);
		$flightGrouping['cheapestPrice'] = $grouping[0]['totalPrice'];
		$flightGrouping['cheapestGroup'] = $grouping[0]['uniqueId'];

		return $flightGrouping;
	}

	private function flightGroupingPattern()
	{
		return array(
			"flights" => "",
			"groups" => array(),
			"totalGroups" => 0,
			"totalFlights" => 0,
			"cheapestPrice" => 0,
			"cheapestGroup" => 0
		);
	}

	private function getTotalUsedFlights($grouping)
	{
		$usedFlights = array();

		foreach ($grouping as $group) {
			$outbound = array_map(function($flight) { return $flight['id']; }, $group['outbound']);
			$inbound = array_map(function($flight) { return $flight['id']; }, $group['inbound']);
			
			$usedFlights = array_merge($usedFlights, $outbound, $inbound);
		}

		$usedFlights = array_unique($usedFlights);

		return count($usedFlights);
	}

}
