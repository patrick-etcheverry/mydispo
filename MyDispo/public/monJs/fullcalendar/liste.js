


document.addEventListener('DOMContentLoaded', function() {



  var listeEl = document.getElementById('liste');
  var liste = new FullCalendar.Calendar(listeEl, {

    plugins: [
      'list', 'bootstrap'
    ],
    defaultView: 'listMonth',
    themeSystem: 'bootstrap',
    contentHeight: "auto",
    events: eventsListe,
    columnHeaderFormat: {
      weekday: 'long'
    },
    locale: 'fr',


    eventRender: function(info) {
      if (info.event.extendedProps.type == "ContraintePro") {
        info.el.querySelector('.fc-title').append(" [PRO] ");
      } else if (info.event.extendedProps.type == "ContraintePerso") {
        info.el.querySelector('.fc-title').append(" [PERSO] ");
      }
    },

  });

  liste.render();

});
