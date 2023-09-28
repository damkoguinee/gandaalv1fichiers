document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    let xmlhttp = new XMLHttpRequest()

    xmlhttp.onreadystatechange = () => {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                let evenements = JSON.parse(xmlhttp.responseText)

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    locale: 'fr',
                    timeZone: "UTC",
                    buttonText: {
                        today: "Ajourdh'hui",
                        month: "Mois",
                        week: "Semaine",
                        day: "Jour",
                        list: "Liste"
                    },
                    events: evenements,
                    nowIndicator: true,
                    selectable: true,
                    // editable: true,
                    // eventResizableFromStart: true,
                    // eventDrop: (infos) => {
                    //     if (!confirm("Etes-vous sûr de vouloir deplacer cet évènement")) {
                    //         infos.revert();                                    
                    //     }
                    // },
                    // eventResize: (infos) => {
                    //     console.log(infos.event.end)
                    // }

                    dateClick: function(info) {
                        // Vérifiez si la date est vide (c'est-à-dire qu'aucun événement n'est déjà prévu à cette heure)
                        if (!info.allDay) {
                            // Redirigez vers un formulaire avec la date de l'événement
                            var formattedDate = info.date.toISOString(); // Formate la date au format ISO

                            var formattedDate = info.date.getUTCFullYear() + '-' + ('0' + (info.date.getUTCMonth() + 1)).slice(-2) + '-' + ('0' + info.date.getUTCDate()).slice(-2);

                            var formattedTime = ('0' + info.date.getUTCHours()).slice(-2) + ':' + ('0' + info.date.getUTCMinutes()).slice(-2);


                            window.location.href = '/damkosport/reservation.php?ajout&dated=' + formattedDate + '&heured=' + formattedTime;
                        }
                    },

                    eventClick: function(info) {
                        console.log(info.event);
                        // Remplissez les données du modal avec les informations de l'événement
                        document.getElementById('eventDate').textContent = info.event.start.toLocaleDateString();
                        document.getElementById('eventDuree').textContent = info.event.extendedProps.duree;
                        document.getElementById('eventTitle').textContent = info.event.title;
                        document.getElementById('eventNbre').textContent = info.event.extendedProps.nbre;
                        document.getElementById('eventMontant').textContent = info.event.extendedProps.montantf;
                        document.getElementById('eventEtat').textContent = info.event.extendedProps.etat;

                        // Affichez le modal
                        var eventModal = document.getElementById('eventModal');
                        var modalInstance = M.Modal.init(eventModal);
                        modalInstance.open();
                    }

                });

                // calendar.on('eventChange', (e) =>{
                //     console.log(e)
                // })

                // calendar.on('dateClick', function(info) {
                //     console.log('clicked on ' + info.dateStr);
                // });
                
                calendar.render();
                M.AutoInit();
            }
            
        }
    }
    xmlhttp.open('get', 'http://localhost/damkosport/calendar_data.php', true)
    
    xmlhttp.send(null)





    
});