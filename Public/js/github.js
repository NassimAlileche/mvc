/**
 *	Inspiré de l'article "Visionner des dépôts Github" page 54 du webdesign magazine hors série n°30
 *  Afficher la liste des dépôts github pour un utilisateur donné
 *
 */

// PubSub ou patron de conception observateur
// + propose un système permettant de découpler des applications.
// + un objet peut alors faire une annonce en réponse à un évènement particulier
// + celle-ci est receptionnée par des écouteurs (observateurs) qui peuvent y répondre et procéder en conséquence


// Initialisation d'un objet qu'on transforme en objet JQuery
var object = $({});
// Annonce de l'évenement
$.subscribe = object.on.bind(object);
// Inception de l'évènement (Pour chaque évènement, on aura : $.publish('key', dataToPass); )
$.publish = object.trigger.bind(object);

var idSearchInput    = '#username';
var elementRepos     = '<ul id="#repos"></ul>';
var idReposContainer = '#repos_container';

// SearchView (écouteur)
// Capture la valeur du champ de recherche quand l'utilisateur est dans le champ et publie l'annonce.
var SearchView = function() {
	$(idSearchInput).on('change', function() {
		var username = $(this).val();
		$.publish('search', username);
	});
};

// RepoCollection
// Récupére la liste des dépôts github pour un utilisateur donné dans un objet json.
// La méthode fetch retourne une promesse HTTP. Comme la requête est asynchrone, elle envoie une notification quand
// les données sont transmises
var RepoCollection = function() {};
RepoCollection.prototype.fetch = function(username) {
	var url = 'https://api.github.com/users/' + username + '/repos';
	return $.getJSON(url);
};

// ReposView
// Affiche les dépôts
var ReposView = function() {
	this.el = $(elementRepos);
	$.subscribe('search', $.proxy(this.update, this));
};
ReposView.prototype = {
	update : function(event, username) {
		(new RepoCollection).fetch(username).done($.proxy(this.renderAll, this));
	},
	renderAll : function(data) {
		this.el.hide().empty();
		$.each(data, $.proxy(this.render, this));
		this.el.appendTo(idReposContainer).show();
	},
	render : function(index, data) {
		this.el.append(this.template(data));
	},
	template : function(data) {
		return [
			'<li>', 
			'<a href="' + data.html_url + '">' + data.name + '</a>',
			'<p>' + data.description + '</p>',
			'</li>'
		].join('');
	}
};

new ReposView();
new SearchView();