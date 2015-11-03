/**
 *
 *
 *
 */
var webservice = {
	url: "http://localhost/webservice/Public/",
	query: function(httpMethod, data) {
		return $.ajax({
			url      : this.url,
			type     : httpMethod,
			datatype : 'json',
			data     : data
		});
	},
	get: function(data) {
		return this.query('get', data);
	},
	post: function(data) {
		return this.query('post', data);
	},
	put: function(data) {
		return this.query('put', data);
	},
	delete: function(data) {
		return this.query('delete', data);
	},
};