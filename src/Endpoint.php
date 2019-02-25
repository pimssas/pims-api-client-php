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
	const INPUT_TYPES					= '/input-types';
	
	const GENRES = '/genres';
	
	const IMPORTS = '/imports';
	
	const PRICERANGES = '/price-ranges';
	
	const PROMOTIONS				= '/promotions';
	const PROMOTIONS_ALLOCATIONS	= '/promotions/:promotion_id/allocations';
	const PROMOTION_TYPES			= '/promotion-types';
	
	const SERIES		= '/series';
	const SERIES_TYPES	= '/series-types';
	
	const STREAMS		= '/streams';
	const STREAM_TYPES	= '/stream-types';
	
	const STREAMS_GROUPS = '/streams-groups';
	
	const SUBSIDIARIES = '/subsidiaries';
	
	const VENUES		= '/venues';
	const VENUES_LABELS	= '/venues/:venue_id/labels';
	const VENUE_TYPES	= '/venue-types';
	
	// Multi-linked types
	const CONTRACT_TYPES	= '/contract-types';
	const SEATING_TYPES		= '/seating-types';
}