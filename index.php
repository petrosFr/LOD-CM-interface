 <!DOCTYPE HTML>
 <?PHP

function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


$user_ip = getUserIP();

?>
<html>
<head> 
<title>Conceptual Model </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
* {
  box-sizing: border-box;
}

body {
  font: 16px Arial;  
}

.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}

input {
  border: 1px solid transparent;
  background-color: #f1f1f1;
  padding: 10px;
  font-size: 16px;
}

input[type=text] {
  background-color: #f1f1f1;
  width: 100%;
}

input[type=submit] {
  background-color: DodgerBlue;
  color: #fff;
  cursor: pointer;
}

.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}

.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}

.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9; 
}

.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}
</style>
</head>



<body>

<h1>Linked Open Data - Conceptual Model</h1>

<ul style="color:red;"> <?php 
if (isset($error)) {
    echo $error;
}
?> </ul>


<?PHP
$date = date('m/d/Y h:i:s a', time());
$txt = getUserIP()." - ".$date;
 $myfile = file_put_contents('logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
 ?>


<form  autocomplete="off" action="page2.php" method="post">
<div class="autocomplete" style="width:300px;">
<select>
<option value="DBpedia">DBpedia</option>
</select> <br> <br>
<input id="myInput" type="text"  name="classname" placeholder="Class Name"> <br> <br>
  <input type="text" name="threshold" pattern="^[1-9][0-9]?$|^100$" placeholder="Threshold (%)" >  <br>
</div>
<input type="submit">
<input type="reset">
</form>

<script>
function autocomplete(inp, arr) {

  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

var countries = ["Abbey","AcademicConference","AcademicJournal","AcademicSubject","Activity","Actor","AdministrativeRegion","AdultActor","Agent","Agglomeration","Aircraft","Airline","Airport","Album","Altitude","AmateurBoxer","Ambassador","AmericanFootballCoach","AmericanFootballLeague","AmericanFootballPlayer","AmericanFootballTeam","Amphibian","AmusementParkAttraction","AnatomicalStructure","Animal","AnimangaCharacter","Anime","Annotation","Arachnid","Archaea","Archeologist","ArcherPlayer","Archipelago","Architect","ArchitecturalStructure","Archive","Area","Arena","Aristocrat","Arrondissement","Artery","Article","ArtificialSatellite","Artist","ArtistDiscography","ArtisticGenre","Artwork","Asteroid","Astronaut","Athlete","Athletics","AthleticsPlayer","Atoll","Attack","AustralianFootballLeague","AustralianFootballTeam","AustralianRulesFootballPlayer","Automobile","AutomobileEngine","AutoRacingLeague","Award","BackScene","Bacteria","BadmintonPlayer","Band","Bank","Baronet","BaseballLeague","BaseballPlayer","BaseballSeason","BaseballTeam","BasketballLeague","BasketballPlayer","BasketballTeam","Bay","Beach","BeachVolleyballPlayer","BeautyQueen","Beer","Beverage","Biathlete","BiologicalDatabase","Biologist","Biomolecule","Bird","Birth","Blazon","BloodVessel","BoardGame","BobsleighAthlete","Bodybuilder","BodyOfWater","Bone","Book","BowlingLeague","Boxer","Boxing","BoxingCategory","BoxingLeague","BoxingStyle","Brain","Brewery","Bridge","BritishRoyalty","Broadcaster","BroadcastNetwork","BrownDwarf","Building","BullFighter","BusCompany","BusinessPerson","Camera","CanadianFootballLeague","CanadianFootballPlayer","CanadianFootballTeam","Canal","Canoeist","Canton","Cape","Capital","CapitalOfRegion","CardGame","Cardinal","CardinalDirection","CareerStation","Cartoon","Case","Casino","Castle","Cat","Caterer","Cave","Celebrity","CelestialBody","Cemetery","Chancellor","ChartsPlacements","Cheese","Chef","ChemicalCompound","ChemicalElement","ChemicalSubstance","ChessPlayer","ChristianBishop","ChristianDoctrine","ChristianPatriarch","Church","City","CityDistrict","ClassicalMusicArtist","ClassicalMusicComposition","Cleric","ClericalAdministrativeRegion","ClericalOrder","ClubMoss","Coach","CoalPit","CollectionOfValuables","College","CollegeCoach","Colour","Comedian","ComedyGroup","Comic","ComicsCharacter","ComicsCreator","ComicStrip","Community","Company","Competition","ConcentrationCamp","Congressman","Conifer","Constellation","Contest","Continent","ControlledDesignationOfOriginWine","Convention","ConveyorSystem","Country","CountrySeat","Crater","Creek","Cricketer","CricketGround","CricketLeague","CricketTeam","Criminal","CrossCountrySkier","Crustacean","CultivatedVariety","Curler","CurlingLeague","Currency","Cycad","CyclingCompetition","CyclingLeague","CyclingRace","CyclingTeam","Cyclist","Dam","Dancer","DartsPlayer","Database","Deanery","Death","Decoration","Deity","Demographics","Department","Depth","Deputy","Desert","Device","DigitalCamera","Dike","Diocese","Diploma","Disease","DisneyCharacter","District","DistrictWaterBoard","Divorce","Document","DocumentType","Dog","Drama","Drug","DTMRacer","Earthquake","Economist","EducationalInstitution","Egyptologist","Election","ElectionDiagram","ElectricalSubstation","Embryology","Employer","EmployersOrganisation","Engine","Engineer","Entomologist","Enzyme","Escalator","EthnicGroup","Eukaryote","EurovisionSongContestEntry","Event","Factory","Family","Farmer","Fashion","FashionDesigner","Fencer","Fern","FictionalCharacter","Fiefdom","FieldHockeyLeague","FigureSkater","File","FillingStation","Film","FilmFestival","Fish","Flag","FloweringPlant","Food","FootballLeagueSeason","FootballMatch","Forest","FormerMunicipality","FormulaOneRacer","FormulaOneRacing","FormulaOneTeam","Fort","Fungus","GaelicGamesPlayer","Galaxy","Game","Garden","Gate","GatedCommunity","Gene","GeneLocation","Genre","GeologicalPeriod","GeopoliticalOrganisation","Ginkgo","GivenName","Glacier","Globularswarm","Gnetophytes","GolfCourse","GolfLeague","GolfPlayer","GolfTournament","GovernmentAgency","GovernmentalAdministrativeRegion","GovernmentCabinet","GovernmentType","Governor","GrandPrix","Grape","GraveMonument","GreenAlga","GridironFootballPlayer","GrossDomesticProduct","GrossDomesticProductPerCapita","Group","Guitar","Guitarist","Gymnast","HandballLeague","HandballPlayer","HandballTeam","HighDiver","Historian","HistoricalAreaOfAuthority","HistoricalCountry","HistoricalDistrict","HistoricalPeriod","HistoricalProvince","HistoricalRegion","HistoricalSettlement","HistoricBuilding","HistoricPlace","HockeyClub","HockeyTeam","Holiday","HollywoodCartoon","Hormone","Horse","HorseRace","HorseRider","HorseRiding","HorseTrainer","Hospital","Host","Hotel","HotSpring","HumanDevelopmentIndex","HumanGene","HumanGeneLocation","Humorist","IceHockeyLeague","IceHockeyPlayer","Ideology","Image","InformationAppliance","Infrastructure","InlineHockeyLeague","Insect","Instrument","Instrumentalist","Intercommunality","InternationalFootballLeagueEvent","InternationalOrganisation","Island","Jockey","Journalist","Judge","LacrosseLeague","LacrossePlayer","Lake","Language","LaunchPad","Law","LawFirm","Lawyer","LegalCase","Legislature","Letter","Library","Lieutenant","LifeCycleEvent","Ligament","Lighthouse","LightNovel","LineOfFashion","Linguist","Lipid","List","LiteraryGenre","Locality","Lock","Locomotive","LunarCrater","Lymph","Magazine","Mammal","Manga","Manhua","Manhwa","Marriage","MartialArtist","MathematicalConcept","Mayor","MeanOfTransportation","Media","Medician","Medicine","Meeting","MemberOfParliament","MemberResistanceMovement","Memorial","MetroStation","MicroRegion","MilitaryAircraft","MilitaryConflict","MilitaryPerson","MilitaryStructure","MilitaryUnit","MilitaryVehicle","Mill","Mine","Mineral","MixedMartialArtsEvent","MixedMartialArtsLeague","MobilePhone","Model","Mollusca","Monarch","Monastery","Monument","Mosque","Moss","MotocycleRacer","Motorcycle","MotorcycleRacingLeague","MotorcycleRider","MotorRace","MotorsportRacer","MotorsportSeason","Mountain","MountainPass","MountainRange","MouseGene","MouseGeneLocation","MovieDirector","MovieGenre","MovingImage","MovingWalkway","MultiVolumePublication","Municipality","Murderer","Muscle","Museum","Musical","MusicalArtist","MusicalWork","MusicComposer","MusicDirector","MusicFestival","MusicGenre","MythologicalFigure","Name","NarutoCharacter","NascarDriver","NationalAnthem","NationalCollegiateAthleticAssociationAthlete","NationalFootballLeagueEvent","NationalFootballLeagueSeason","NationalSoccerClub","NaturalEvent","NaturalPlace","NaturalRegion","NCAATeamSeason","Nerve","NetballPlayer","Newspaper","NobelPrize","Noble","NobleFamily","Non-ProfitOrganisation","NordicCombined","Novel","NuclearPowerStation","Ocean","OfficeHolder","OldTerritory","OlympicEvent","OlympicResult","Olympics","OnSiteTransportation","Openswarm","Opera","Organ","Organisation","OrganisationMember","Orphan","OverseasDepartment","PaintballLeague","Painter","Painting","Parish","Park","Parliament","PenaltyShootOut","PeriodicalLiterature","PeriodOfArtisticStyle","Person","PersonalEvent","PersonFunction","Philosopher","PhilosophicalConcept","Photographer","Place","Planet","Plant","Play","PlayboyPlaymate","PlayWright","Poem","Poet","PokerPlayer","PoliticalConcept","PoliticalFunction","PoliticalParty","Politician","PoliticianSpouse","PoloLeague","Polyhedron","Polysaccharide","Pope","PopulatedPlace","Population","Port","PowerStation","Prefecture","PrehistoricalPeriod","Presenter","President","Priest","PrimeMinister","Prison","Producer","Profession","Professor","ProgrammingLanguage","Project","ProtectedArea","Protein","ProtohistoricalPeriod","Province","Psychologist","PublicService","PublicTransitSystem","Publisher","Pyramid","Quote","Race","Racecourse","RaceHorse","RaceTrack","RacingDriver","RadioControlledRacingLeague","RadioHost","RadioProgram","RadioStation","RailwayLine","RailwayStation","RailwayTunnel","RallyDriver","Ratio","Rebellion","RecordLabel","RecordOffice","Referee","Reference","Regency","Region","Relationship","Religious","ReligiousBuilding","ReligiousOrganisation","Reptile","ResearchProject","RestArea","Restaurant","Resume","River","Road","RoadJunction","RoadTunnel","Rocket","RocketEngine","RollerCoaster","RomanEmperor","RouteOfTransportation","RouteStop","Rower","Royalty","RugbyClub","RugbyLeague","RugbyPlayer","Saint","Sales","SambaSchool","Satellite","School","ScientificConcept","Scientist","ScreenWriter","Sculptor","Sculpture","Sea","Senator","SerialKiller","Settlement","Ship","ShoppingMall","Shrine","Singer","Single","SiteOfSpecialScientificInterest","Skater","Ski_jumper","SkiArea","Skier","SkiResort","Skyscraper","SnookerChamp","SnookerPlayer","SnookerWorldRanking","SoapCharacter","Soccer","SoccerClub","SoccerClubSeason","SoccerLeague","SoccerLeagueSeason","SoccerManager","SoccerPlayer","SoccerTournament","SocietalEvent","SoftballLeague","Software","SolarEclipse","Song","SongWriter","Sound","Spacecraft","SpaceMission","SpaceShuttle","SpaceStation","Species","SpeedSkater","SpeedwayLeague","SpeedwayRider","SpeedwayTeam","Sport","SportCompetitionResult","SportFacility","SportsClub","SportsEvent","SportsLeague","SportsManager","SportsSeason","SportsTeam","SportsTeamMember","SportsTeamSeason","Square","SquashPlayer","Stadium","Standard","Star","State","StatedResolution","Station","Statistic","StillImage","StormSurge","Stream","Street","SubMunicipality","SumoWrestler","SupremeCourtOfTheUnitedStatesCase","Surfer","Surname","Swarm","Swimmer","Synagogue","SystemOfLaw","TableTennisPlayer","Tax","Taxon","TeamMember","TeamSport","TelevisionDirector","TelevisionEpisode","TelevisionHost","TelevisionPersonality","TelevisionSeason","TelevisionShow","TelevisionStation","Temple","TennisLeague","TennisPlayer","TennisTournament","TermOfOffice","Territory","Theatre","TheatreDirector","TheologicalConcept","TimePeriod","TopicalConcept","Tournament","Tower","Town","TrackList","TradeUnion","Train","TrainCarriage","Tram","TramStation","Treadmill","Treaty","Tunnel","Type","UndergroundJournal","UnitOfWork","University","Unknown","Valley","Vein","Venue","Vicar","VicePresident","VicePrimeMinister","VideoGame","VideogamesLeague","Village","Vodka","VoiceActor","Volcano","VolleyballCoach","VolleyballLeague","VolleyballPlayer","Watermill","WaterPoloPlayer","WaterRide","WaterTower","WaterwayTunnel","Weapon","Website","Windmill","WindMotor","Wine","WineRegion","Winery","WinterSportPlayer","WomensTennisAssociationTournament","Work","WorldHeritageSite","Wrestler","WrestlingEvent","Writer","WrittenWork","Year","YearInSpaceflight","Zoo"]


autocomplete(document.getElementById("myInput"), countries);
</script>

</body>
</html> 
