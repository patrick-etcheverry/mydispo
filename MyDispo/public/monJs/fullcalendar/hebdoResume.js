


document.addEventListener('DOMContentLoaded', function() {



  setTimeout(function(){var hebdoEl = document.getElementById('hebdo');
  var hebdo = new FullCalendar.Calendar(hebdoEl, {

    plugins: [
      'timeGrid', 'bootstrap'
    ],
    now: "2013-12-01T00:00:00",
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
    events: events,
    columnHeaderFormat: {
      weekday: 'long'
    },
    locale: 'fr',
    header: {
      left: '',
      center: '',
      right: ''
    },

    eventRender: function(info) {
      if (info.event.extendedProps.type == "ContraintePro") {
        info.el.querySelector('.fc-title').append(" [PRO] ");
      } else if (info.event.extendedProps.type == "ContraintePerso") {
        info.el.querySelector('.fc-title').append(" [PERSO] ");
      }
    },

  });

  hebdo.render();

},50);});
