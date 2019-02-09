<?php

namespace Pims\Api;

/**
 * List all endpoints that can be called via the API
 *
 * @package Pims\Api
 */
interface Endpoint {
	const BINDINGS			= '/bindings';
	const BINDINGS_STATES	= '/bindings/:binding_id/states';
	const BINDINGS_LOGS		= '/bindings/:binding_id/logs';
	
	const CATEGORIES = '/categories';
	
	const CHANNELS = '/channels';
	
	const EVENTS 						= '/events';
	const EVENTS_CAPACITIES 			= '/events/:event_id/capacities';
	const EVENTS_CATEGORIES 			= '/events/:event_id/categories';
	const EVENTS_CHANNELS 				= '/events/:event_id/channels';
	const EVENTS_TICKETCOUNTS			= '/events/:event_id/ticket-counts';
	const EVENTS_TICKETCOUNTS_DETAILED	= '/events/:event_id/ticket-counts/detailed';
	
	const GENRES = '/genres';
	
	const IMPORTS = '/imports';
	
	const PRICERANGES = '/price-ranges';
	
	const PROMOTIONS = '/promotions';
	
	const SERIES = '/series';
	
	const STREAMS = '/streams';
	
	const STREAMS_GROUPS = '/streams-groups';
	
	const SUBSIDIARIES = '/subsidiaries';
	
	const VENUES = '/venues';
}