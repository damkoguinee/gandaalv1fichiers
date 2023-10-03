document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    let xmlhttp = new XMLHttpRequest()

    xmlhttp.onreadystatechange = () => {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                let evenements = JSON.parse(xmlhttp.responseText)

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    
                    initialView: 'timeGridDay',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        
                    },
                    // minTime: '08:00:00',
                    // maxTime: '18:00:00',
                    allDaySlot: false,
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


                            window.location.href = 'event.php?ajoutEvent&type=' + formattedDate + '&heured=' + formattedTime;
                        }
                    },

                    eventClick: function(info) {
                        console.log(info.event);
                        // Remplissez les données du modal avec les informations de l'événement
                        // document.getElementById('eventDate').textContent = info.event.start.toLocaleDateString();
                        // document.getElementById('eventDuree').textContent = info.event.extendedProps.duree;
                        // document.getElementById('eventTitle').textContent = info.event.title;
                        // document.getElementById('eventNbre').textContent = info.event.extendedProps.nbre;
                        // document.getElementById('eventMontant').textContent = info.event.extendedProps.montantf;
                        // document.getElementById('eventEtat').textContent = info.event.extendedProps.etat;

                        // // Affichez le modal
                        // var eventModal = document.getElementById('eventModal');
                        // var modalInstance = M.Modal.init(eventModal);
                        // modalInstance.open();
                        //console.log(info.event.extendedProps.enseignant);
                        window.location.href = 'ajout_absencej.php?id=' + info.event.id + '&codens=' + info.event.extendedProps.enseignant + '&appelj=' + info.event.extendedProps.appelj + '&nheure=' + info.event.extendedProps.nheure + '&hdebut=' + info.event.extendedProps.hdebut + '&matn=' + info.event.extendedProps.matiere + '&semestre=' + info.event.extendedProps.semestre + '&classe=' + info.event.extendedProps.classe;
                        // <a class="lienemp" href="planingjv0.php?id=<?=$event->id;?>&codens=<?=$event->codensp;?>&appelj=<?=$appelj;?>&nheure=<?=$deltaval;?>&hdebut=<?=$debut;?>&matn=<?=$event->codem;?>&semestre=<?=$semcourant;?>&classe=<?=$event->nomgrp;?>"></a>
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

    // var groupe; // Déclarer la variable en dehors de la fonction de rappel

    // var select = document.getElementById("groupe");
    // select.addEventListener("change", function() {
    //     groupe = select.value;
    //     faireQuelqueChoseAvecGroupe(groupe);
    // });
    
    // // Vous pouvez maintenant accéder à la variable 'groupe' en dehors de l'élément 'select'
    // // chaque fois qu'une nouvelle valeur est sélectionnée
    // function faireQuelqueChoseAvecGroupe(groupe) {    
    //     // Ajoutez ici le code pour effectuer une action avec 'groupe'
    // }
        //xmlhttp.open('get', 'http://localhost/gandaal/calendar_data.php?groupe=' + groupe + '&enseignant=' + enseignant, true)

        xmlhttp.open('get', 'https://groupescolairebikaz.com/gandaalbikaz/calendar_data.php?groupe=' + groupe + '&enseignant=' + enseignant, true)
        xmlhttp.send(null)
    





    
});