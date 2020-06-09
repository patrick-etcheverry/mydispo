document.addEventListener('DOMContentLoaded', function() {
  var mensuelEl = document.getElementById('mensuel');
  var mensuel = new FullCalendar.Calendar(mensuelEl, {
    plugins: [
      'interaction', 'dayGrid'
    ],
    selectable: true,
    editable: true,
    height: 700,
    events: events,
    contentHeight: 'auto',
    locale: 'fr',
    select: function(arg) {

      closeNav();
      var title = prompt('Titre du créneau:');
      if (title) { // si un titre d'événement a été saisi et que la limite d'événement autorisés n'a pas été dépassée
        mensuel.addEvent({title: title, start: arg.start, end: arg.end, allDay: true, classNames: ['plusBord']})
      }

      mensuel.unselect();
      mensuel.getEvents().forEach(event => {
        event.setProp("borderColor", "white");
      });
    },
    eventRender: function(info) {
      var dateDeb = mensuel.formatDate(info.event.start, {
        weekday: 'long',
        day: 'numeric',
        year: 'numeric',
        month: 'long',
        locale: 'fr'
      });
      var dateFin = mensuel.formatDate(info.event.end, {
        weekday: 'long',
        day: 'numeric',
        year: 'numeric',
        month: 'long',
        locale: 'fr'
      });
      var contenu = "Titre : " + info.event.title + "</br>Début : " + dateDeb + "</br>Fin : " + dateFin;

      var tooltip = new tippy(info.el, {
        allowHTML: true,
        content: contenu,
        trigger: 'mouseenter',
        sticky: true,
        animation: 'shift-toward',
        maxWidth: 200
      });

    },
    eventClick: function(info) {
      mensuel.getEvents().forEach(event => {
        event.setProp("borderColor", "white");
      });
      document.getElementById('nomcreneau').innerHTML = "Contrainte " + info.event.title;
      document.getElementById('titrevt').value = info.event.title;
      info.event.setProp("borderColor", "red");

      document.getElementById('apply').onclick = function() {
        if (document.getElementById('titrevt').value != '') {
          info.event.setProp("title", document.getElementById('titrevt').value);
          document.getElementById("nomcreneau").innerHTML = "Contrainte " + info.event.title;
          mensuel.rerenderEvents();
        }

      };
      document.getElementById('remove').onclick = function() {
        if (confirm("Voulez vous vraiment supprimer ce créneau ?")) {
          info.event.remove();
        }
        closeNav();
      };
      document.getElementById('close').onclick = function() {
        closeNav();
        info.event.setProp("borderColor", "white");
      };
      openNav();
    }

  });

  document.getElementById('submit').onclick = function() {
    supprimerDesCreneaux("evenement","");
    creneaux = mensuel.getEvents(); //on récupère tous les événements du calendrier sous forme d'un tableau
    creneaux.forEach(creneau => enregistrerUnCreneau(creneau.start.toISOString(), creneau.end.toISOString(), creneau.title, "evenement")); //pour chaque élément du tableau, c'est à dire pour chaque événement, on envoie sa date de début, sa date de fin et son titre au serveur
  };

  mensuel.render();

});
