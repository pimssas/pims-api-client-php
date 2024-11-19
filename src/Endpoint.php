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
	
	const CATEGORIES		= '/categories';
	const CATEGORIES_GROUPS	= '/categories-groups';
	
	const CHANNELS			= '/channels';
	const CHANNELS_GROUPS 	= '/channels-groups';
	
	const COLORS			= '/colors';
	
	const COUNTRIES			= '/countries';
	
	const EVENTS 						= '/events';
	const EVENTS_SERIES 				= '/events-series';
	const EVENTS_CAPACITIES 			= '/events/:event_id/capacities';
	const EVENTS_CATEGORIES 			= '/events/:event_id/categories';
	const EVENTS_CHANNELS 				= '/events/:event_id/channels';
	const EVENTS_PROMOTIONS 			= '/events/:event_id/promotions';
	const EVENTS_TICKETCOUNTS			= '/events/:event_id/ticket-counts';
	const EVENTS_TICKETCOUNTS_DETAILED	= '/events/:event_id/ticket-counts/detailed';
	const INPUT_TYPES					= '/input-types';

	const FEES			= '/fees';
	const FEES_GROUPS	= '/fees-groups';

	const GENRES = '/genres';
	
	const IDENTITIES = '/identities';
	
	const IMPORTS = '/imports';
	
	const ORDERS = '/orders';
	
	const PRICERANGES			= '/price-ranges';
	const PRICERANGES_GROUPS	= '/price-ranges-groups';
	
	const PROMOTIONS				= '/promotions';
	const PROMOTIONS_ALLOCATIONS	= '/promotions/:promotion_id/allocations';
	const PROMOTION_TYPES			= '/promotion-types';
	
	const GUESTS		= '/events/:event_id/guests';
	const GUESTS_QUOTAS	= '/events/:event_id/guests-quotas';
	
	const STATS_EVENTS_CAPACITIES_BY_DATES			= '/stats/events-capacities/by-dates';
	const STATS_EVENTS_SALES_BY_DATES				= '/stats/events-sales/by-dates';
	const STATS_EVENTS_SALES_BY_EVENTS_BY_DATES		= '/stats/events-sales/by-events/by-dates';
	const STATS_EVENTS_SALES_DELTA_BY_CATEGORIES	= '/stats/events-sales-delta/by-categories';
	const STATS_EVENTS_SALES_DELTA_BY_CHANNELS		= '/stats/events-sales-delta/by-channels';
	const STATS_EVENTS_SALES_DELTA_BY_PRICE_RANGES	= '/stats/events-sales-delta/by-price-ranges';
	
	const SERIES			= '/series';
	const SERIES_EVENTS 	= '/series/:series_id/events';
	const SERIES_TYPES		= '/series-types';
	const SERIES_PROMOTIONS	= '/series/:series_id/promotions';
	
	const STREAMS		= '/streams';
	const STREAM_TYPES	= '/stream-types';
	
	const STREAMS_GROUPS = '/streams-groups';
	
	const SUBSIDIARIES = '/subsidiaries';
	
	const TAGS = '/tags';
	
	const TICKETS			= '/tickets';
	const TICKETS_MOVEMENTS	= '/tickets/:ticket_id/movements';
	
	const TICKET_COUNTS_IDS = '/ticket-counts-ids';
	
	const USERS 		= '/users';
	const USER_PROFILES = '/user-profiles';
	
	const VENUES		= '/venues';
	const VENUES_LABELS	= '/venues/:venue_id/labels';
	const VENUE_TYPES	= '/venue-types';
	
	// Multi-linked types
	const CONTRACT_TYPES	= '/contract-types';
	const SEATING_TYPES		= '/seating-types';
}
