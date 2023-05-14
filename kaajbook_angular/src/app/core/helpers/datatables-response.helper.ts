/**
 *	Returns array response for datatable pagination length, records par page length etc.
 *
 *	@class DatatablesResponse
*/
export class DatatablesResponse {
	data: any[];
	draw: number;
	recordsFiltered: number;
	recordsTotal: number;
}
