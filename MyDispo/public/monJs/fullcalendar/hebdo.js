document.addEventListener('DOMContentLoaded', function() {

  var hebdoEl = document.getElementById('hebdo');
  var hebdo = new FullCalendar.Calendar(hebdoEl, {

    plugins: [
      'timeGrid', 'interaction', 'bootstrap'
    ],
    defaultView: 'timeGridWeek',
    themeSystem: 'bootstrap',
    contentHeight: "auto",
    allDaySlot: false,
    slotDuration: echelle,
    slotLabelInterval: echelle,
    minTime: heureDebut,
    maxTime: heureFin,
    weekNumberCalculation: "ISO",
    weekends: false,
    selectable: true,
    events: events,
    columnHeaderFormat: {
      weekday: 'long'
    },
    editable: true,
    locale: 'fr',
    header: {
      left: '',
      center: '',
      right: ''
    },

    select: function(arg) {

      closeNav();
      var title = prompt('Titre de la contrainte:');
      if (title) { // si un titre d'événement a été saisi et que la limite d'événement autorisés n'a pas été dépassée
        hebdo.addEvent({
          title: title,
          start: arg.start,
          end: arg.end,
          classNames: ['plusBord']
        })
      }

      hebdo.unselect();
      hebdo.getEvents().forEach(event => {
        event.setProp("borderColor", "white");
      });
    },
    eventRender: function(info) {
      var dateDeb = hebdo.formatDate(info.event.start, {
        weekday: 'long',
        hour: '2-digit',
        minute: '2-digit',
        locale: 'fr'
      });
      var dateFin = hebdo.formatDate(info.event.end, {
        weekday: 'long',
        hour: '2-digit',
        minute: '2-digit',
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
      hebdo.getEvents().forEach(event => {
        event.setProp("borderColor", "white");
      });
      document.getElementById('nomcreneau').innerHTML = "Contrainte " + info.event.title;
      document.getElementById('titrevt').value = info.event.title;
      info.event.setProp("borderColor", "red");

      document.getElementById('apply').onclick = function() {
        if (document.getElementById('titrevt').value != '') {
          info.event.setProp("title", document.getElementById('titrevt').value);
          document.getElementById("nomcreneau").innerHTML = "Contrainte " + info.event.title;
          hebdo.rerenderEvents();
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
    supprimerDesCreneaux("zoneGrisee", "");
    creneaux = hebdo.getEvents(); //on récupère tous les événements du calendrier sous forme d'un tableau
    creneaux.forEach(creneau => enregistrerUnCreneau(creneau.start.toISOString(), creneau.end.toISOString(), creneau.title, "zoneGrisee"));
  };

  hebdo.render();
});
