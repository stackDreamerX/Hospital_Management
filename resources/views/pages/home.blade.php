@extends('layout')

@section('content')


<section class="hero theme-blue-pattern hero--hasDesktopImage ">
    <div class="hero__image hero__image--shadeBottomDesktop">
        <div class="container">
            <div class="hero__text ">
                <h1 class="hero__title">We're here when you need us — for every care in the world.</h1>
            </div>
        </div>
    </div>
</section>


<div class="homepage-content">


    <section class="story-panel js-story-panel story-panel__white-background" id="aenterprise_home-page-cards">
        <div class="story-panel__carousel js-story-panel__carousel container story-panel__no-carousel">
            <ul class="story-panel__cards-container js-story-panel__cards-container story-panel__full-width">

        <li class="story-layout">
            <div class="story-panel__card js-story-panel__card" style="height: 443.513px;">
                <div class="story-panel__card-content story-panel__no-selection ">
                         <a class="story-panel__image-link" href="{{ route('users.staff') }}">
                            <div class="story-panel__image-overlay">
                                <img class="js-story-panel__image" src="public/FrontEnd/images/find-a-doctor-card-new.jpg" width="634" height="356" alt="Find a Doctor">
                            </div>
                        </a>
                        <a class="story-panel__title-link" href="/staff"><h3 class="story-panel__title">Our Doctors</h3></a>
                        <p class="story-panel__description">Search by name, specialty, location and more.</p>
                        <a class="story-panel__button button" href="{{ route('users.staff') }}">Find a doctor</a>
                </div>
            </div>
        </li>
        <li class="story-layout">
            <div class="story-panel__card js-story-panel__card" style="height: 443.513px;">
                <div class="story-panel__card-content story-panel__no-selection ">
                                            <a class="story-panel__image-link" href="{{ route('users.locations') }}">
                            <div class="story-panel__image-overlay">
                                <img class="js-story-panel__image" src="public/FrontEnd/images/locations-card-new.png" width="634" height="356" alt="Locations">
                            </div>
                        </a>
                                <a class="story-panel__title-link" href="{{ route('users.locations') }}"><h3 class="story-panel__title">Locations &amp; Directions</h3></a>
                                <p class="story-panel__description">Find any of our 300+ locations.</p>
                                <a class="story-panel__button button" href="{{ route('users.locations') }}">Get directions</a>
                </div>
            </div>
        </li>
        <li class="story-layout">
            <div class="story-panel__card js-story-panel__card" style="height: 443.513px;">
                <div class="story-panel__card-content story-panel__no-selection ">
                                            <a class="story-panel__image-link" href="/patients/information/access"> 
                            <div class="story-panel__image-overlay">
                                <img class="js-story-panel__image" src="public/FrontEnd/images/appointments-card.jpg" width="634" height="356" alt="Appointments">
                            </div>
                        </a>
                                            <a class="story-panel__title-link" href="/patients/information/access"><h3 class="story-panel__title">Appointments</h3></a>
                                            <p class="story-panel__description">Get the in person or virtual care you need.</p>
                                            <a class="story-panel__button button" href="{{ route('users.appointments') }}">Schedule now</a>
                </div>
            </div>
        </li>
            </ul>
        </div>
        <a class="story-panel__nav-button story-panel__nav-button--prev js-story-panel__nav-button--prev story-panel__hide"><img src="public/FrontEnd/images/icon-arrow-left.svg"></a>
        <a class="story-panel__nav-button story-panel__nav-button--next js-story-panel__nav-button--next story-panel__hide"><img src="public/FrontEnd/images/icon-arrow-right.svg"></a>
        <div class="story-panel__pagination js-story-panel__pagination"></div>
     </section>

  <section class="care-widget js-care-widget js-care-widget--care-pages care-widget--care-pages" id="call-out-panel">
    <div class="care-widget__header">
      <div class="care-widget__header-wrapper">
        <div class="care-widget__logo js-care-widget__logo">
                          <svg version="1.1" viewBox="0 0 73 73" x="0px" xmlns="http://www.w3.org/2000/svg" y="0px">
                    <g>
                        <path class="-path" d="M55.9,22.3v12.5H67c3,0,5.3-2.4,5.3-5.3V5.9c0-3-2.4-5.3-5.3-5.3H43.4c-3,0-5.3,2.4-5.3,5.3v11.1h12.5 C53.6,16.9,55.9,19.3,55.9,22.3z M15.8,22.3v12.5H4.8c-3,0-5.3-2.4-5.3-5.3V5.9c0-3,2.4-5.3,5.3-5.3h23.6c3,0,5.3,2.4,5.3,5.3v11.1 H21.2C18.2,16.9,15.8,19.3,15.8,22.3z"></path>
                        <path class="-path" d="M15.8,51.7V39.2H4.8c-3,0-5.3,2.4-5.3,5.3v23.6c0,3,2.4,5.3,5.3,5.3h23.6c3,0,5.3-2.4,5.3-5.3V57H21.2 C18.2,57,15.8,54.7,15.8,51.7z M55.9,51.7V39.2H67c3,0,5.3,2.4,5.3,5.3v23.6c0,3-2.4,5.3-5.3,5.3H43.4c-3,0-5.3-2.4-5.3-5.3V57 h12.5C53.6,57,55.9,54.7,55.9,51.7z"></path>
                    </g>
                </svg>

        </div>
        <div class="care-widget__title js-care-widget__title">Health Library</div>
      </div>
    </div>
    <div class="care-widget__content">
      <ul class="care-widget__ul">
              <li>
                <a href="/health/diseases" disablewebedit="True">Diseases &amp; Conditions</a>
              </li>
              <li>
                <a href="/health/diagnostics" disablewebedit="True">Diagnostics &amp; Testing</a>
              </li>
              <li>
                <a href="/health/treatments" disablewebedit="True">Treatments &amp; Procedures</a>
              </li>
              <li>
                <a href="/health/body" disablewebedit="True">Body Systems &amp; Organs</a>
              </li>
              <li>
                <a href="/health/drugs" disablewebedit="True">Drugs, Devices &amp; Supplements</a>
              </li>
      </ul>
    </div>
  </section>

	<section class="cta-panel js-cta-panel" id="aenterprise_home-page">
		<div class="container cta-panel__container js-cta-panel__container cta-panel__container--wide">
				<div class="cta-panel__card js-cta-panel__card cta-panel__layout">
					<div class="cta-panel__card-content">
							<img src="public/FrontEnd/images/icon-appointments-dark-blue.svg" alt="Getting an appointment at Medic Hospital is easy. Schedule using any of these convenient options.">
													<h2 class="cta-panel__card-header" style="height: auto;">Get Care</h2>
													<p style="height: auto;">Getting an appointment at Medic Hospital is easy. Schedule using any of these convenient options.</p>
													<hr>
								<div class="js-cta-panel__links" style="height: auto;">
<a href="tel://866.320.4573" disablewebedit="True">037.864.9957</a><a href="/webappointment" disablewebedit="True">Appointment request form</a><a href="/virtual-visits" disablewebedit="True">Virtual visits</a><a href="/patients/information/access/express-care-clinics" disablewebedit="True">Express Care and Urgent Care</a><a href="/online-services/virtual-second-opinions" disablewebedit="True">Virtual second opinions</a>								</div>
					</div>
				</div>
				<div class="cta-panel__card js-cta-panel__card cta-panel__layout">
					<div class="cta-panel__card-content">
							<img src="public/FrontEnd/images/icon-stay-healthy-light-blue.svg" alt="Find health and wellness information to help you and your family live a bit healthier each day.">
													<h2 class="cta-panel__card-header" style="height: 44px;">Live Healthier</h2>
													<p style="height: 96px;">Find health and wellness information to help you and your family live a bit healthier each day.</p>
													<hr>
								<div class="js-cta-panel__links" style="height: 191.5px;">
<a href="/" disablewebedit="True" target="_blank">Health news and trends</a><a href="/health-essentials-sign-up?utm_medium=email&amp;utm_source=dynamics&amp;utm_campaign=he&amp;utm_content=signuppage" disablewebedit="True">Sign up for our newsletter</a><a href="/podcasts/health-essentials" disablewebedit="True">Tune in to our podcast</a><a href="/diet-food-fitness/nutrition" disablewebedit="True" target="_blank">Nutrition and healthy eating</a><a href="/diet-food-fitness/exercise-fitness" disablewebedit="True" target="_blank">Exercise &amp; Fitness</a>								</div>
					</div>
				</div>
				<div class="cta-panel__card js-cta-panel__card cta-panel__layout">
					<div class="cta-panel__card-content">
							<img src="public/FrontEnd/images/icon-need-help-green.svg" alt="Have a question? We want to make it easy to find what you&#39;re looking for. Get answers online fast.">
													<h2 class="cta-panel__card-header" style="height: 44px;">Need Help?</h2>
													<p style="height: 96px;">Have a question? We want to make it easy to find what you're looking for. Get answers online fast.</p>
													<hr>
								<div class="js-cta-panel__links" style="height: 191.5px;">
<a href="tel://216.444.2200" disablewebedit="True">0924.184.107</a><a href="patients/visitor-information" disablewebedit="True">Visiting our main campus</a><a href="/patients/billing-finance" disablewebedit="True">Pay your bill online</a><a href="/online-services/mychart" disablewebedit="True">MyChart</a><a href="/patients/information/medical-records" disablewebedit="True">Request your medical records</a>								</div>
					</div>
				</div>
		</div>
	</section>

  <section class="spotlight-panel" id="aenterprise_home-page_why-choose">
    <h2 class="container--md text-center">Why Choose Medic Hospital?</h2>
  <div class="spotlight-panel__container js-spotlight-panel__container container">
        <div class="spotlight-panel__card js-spotlight-panel__card spotlight-layout spotlight-layout--1">

          <div class="spotlight-panel__icon-section">

            <img src="public/FrontEnd/images/patient-centered-care.svg" alt="patient centered care icon">
          </div>
          
            <h3>Patient-centered care:</h3>
          <p>We don’t just care for your health conditions. We care about you. That means <a href="/staff">our providers</a> take the time to listen to what’s important to you before recommending next steps.</p>        </div>
        <div class="spotlight-panel__card js-spotlight-panel__card spotlight-layout spotlight-layout--1">

          <div class="spotlight-panel__icon-section">

            <img src="public/FrontEnd/images/national-recognition.svg" alt="national recognition icon">
          </div>
          
            <h3>National recognition:</h3>
          <p>Medic Hospital is recognized in the U.S. and throughout the world for its expertise and care.</p>        </div>
        <div class="spotlight-panel__card js-spotlight-panel__card spotlight-layout spotlight-layout--1">

          <div class="spotlight-panel__icon-section">

            <img src="public/FrontEnd/images/skilled-collaborative-providers.svg" alt="skilled and collaborative providers icon">
          </div>
          
            <h3>Collaborative providers:</h3>
          <p>You’ll get care from board-certified and fellowship trained experts who work together to create a treatment plan just for you. Only the highest standards ensure <a href="/departments/patient-experience/depts/quality-patient-safety/treatment-outcomes">excellent outcomes</a>.</p>        </div>
        <div class="spotlight-panel__card js-spotlight-panel__card spotlight-layout spotlight-layout--1">

          <div class="spotlight-panel__icon-section">

            <img src="public/FrontEnd/images/innovation-research.svg" alt="Innovation and Research Icon">
          </div>
          
            <h3>Innovation and research:</h3>
          <p>We’re focused on today — and tomorrow. Our focus on <a href="/research">research</a> and offering the latest options means you can find a wide range of <a href="/clinical-trials">clinical trials</a> and other care that you can’t find elsewhere.</p>        </div>
    </div>
    <div class="l-1col--1 container"><a class="button button--secondary spotlight-panel__expand js-spotlight-panel__expand hide">View more reasons:</a></div>
    <div class="l-1col--1 container"><a href="/#aenterprise_home-page_why-choose" class="button button--secondary spotlight-panel__collapse js-spotlight-panel__collapse hide">View less:</a></div>
  </section>



    <section class="story-panel js-story-panel story-panel__white-background" id="aenterprise_home-page-care-near-you">
            <h2 class="story-panel__heading container container--md text-center">Medic Hospital Care Near You</h2>
        <div class="story-panel__carousel js-story-panel__carousel container story-panel__has-slider" style="height: 290.675px;">
            <ul class="story-panel__cards-container js-story-panel__cards-container" style="margin-left: 0%; width: 200%;">

        <li class="story-layout" style="width: calc(12.5% - 34px);">
            <div class="story-panel__card js-story-panel__card" style="height: 258.8px;">
                <div class="story-panel__card-content story-panel__no-selection story-panel__no-button">
                                            <a class="story-panel__image-link" href="/locations/ohio-locations">
                            <div class="story-panel__image-overlay">
                                <img class="js-story-panel__image" src="public/FrontEnd/images/cleveland-clinic-main-2.jpg" width="634" height="356" alt="Medic Hospital main campus">
                            </div>
                        </a>
                                                                <a class="story-panel__title-link" href="/locations/ohio-locations"><h3 class="story-panel__title">Medic Hospital Locations in Ohio</h3></a>
                                                                            </div>
            </div>
        </li>
        <li class="story-layout" style="width: calc(12.5% - 34px);">
            <div class="story-panel__card js-story-panel__card" style="height: 258.8px;">
                <div class="story-panel__card-content story-panel__no-selection story-panel__no-button">
                                            <a class="story-panel__image-link" href=/florida/locations">
                            <div class="story-panel__image-overlay">
                                <img class="js-story-panel__image" src="public/FrontEnd/images/cleveland-clinic-florida-2.jpg" width="634" height="356" alt="Medic Hospital Florida">
                            </div>
                        </a>
                                                                <a class="story-panel__title-link" href="/florida/locations"><h3 class="story-panel__title">Medic Hospital Locations in Florida</h3></a>
                                                                            </div>
            </div>
        </li>
        <li class="story-layout" style="width: calc(12.5% - 34px);">
            <div class="story-panel__card js-story-panel__card" style="height: 258.8px;">
                <div class="story-panel__card-content story-panel__no-selection story-panel__no-button">
                                            <a class="story-panel__image-link" href="/en/Pages/default.aspx">
                            <div class="story-panel__image-overlay">
                                <img class="js-story-panel__image" src="public/FrontEnd/images/cleveland-clinic-abu-dhabi-2.jpg" width="634" height="356" alt="Medic Hospital Abu Dhabi">
                            </div>
                        </a>
                                                                <a class="story-panel__title-link" href="/Pages/default.aspx"><h3 class="story-panel__title">Medic Hospital Abu Dhabi</h3></a>
                                                                            </div>
            </div>
        </li>
        <li class="story-layout" style="width: calc(12.5% - 34px);">
            <div class="story-panel__card js-story-panel__card" style="height: 258.8px;">
                <div class="story-panel__card-content story-panel__no-selection story-panel__no-button">
                                            <a class="story-panel__image-link" href="/canada">
                            <div class="story-panel__image-overlay">
                                <img class="js-story-panel__image" src="public/FrontEnd/images/cleveland-clinic-canada-2.jpg" width="634" height="356" alt="Medic Hospital Canada">
                            </div>
                        </a>
                                                                <a class="story-panel__title-link" href="/canada"><h3 class="story-panel__title">Medic Hospital Canada</h3></a>
                                                                            </div>
            </div>
        </li>
        <li class="story-layout story-panel__fade" style="width: calc(12.5% - 34px);">
            <div class="story-panel__card js-story-panel__card" style="height: 234.8px;">
                <div class="story-panel__card-content story-panel__no-selection story-panel__no-button">
                                            <a class="story-panel__image-link" href=".uk/">
                            <div class="story-panel__image-overlay">
                                <img class="js-story-panel__image" src="public/FrontEnd/images/cleveland-clinic-london-2.jpg" width="654" height="367" alt="Medic Hospital London">
                            </div>
                        </a>
                                                                <a class="story-panel__title-link" href=".uk/"><h3 class="story-panel__title">Medic Hospital London</h3></a>
                                                                            </div>
            </div>
        </li>
        <li class="story-layout story-panel__fade" style="width: calc(12.5% - 34px);">
            <div class="story-panel__card js-story-panel__card" style="height: 234.8px;">
                <div class="story-panel__card-content story-panel__no-selection story-panel__no-button">
                                            <a class="story-panel__image-link" href="/locations/nevada">
                            <div class="story-panel__image-overlay">
                                <img class="js-story-panel__image" src="public/FrontEnd/images/cleveland-clinic-las-vegas-2.jpg" width="634" height="356" alt="Medic Hospital Nevada">
                            </div>
                        </a>
                                                                <a class="story-panel__title-link" href="/locations/nevada"><h3 class="story-panel__title">Medic Hospital Nevada</h3></a>
                                                                            </div>
            </div>
        </li>
        <li class="story-layout story-panel__fade" style="width: calc(12.5% - 34px);">
            <div class="story-panel__card js-story-panel__card" style="height: 234.8px;">
                <div class="story-panel__card-content story-panel__no-selection story-panel__no-button">
                                            <a class="story-panel__image-link" href="/patients/international">
                            <div class="story-panel__image-overlay">
                                <img class="js-story-panel__image" src="public/FrontEnd/images/global-patient-services-2.jpg" width="634" height="355" alt="Flags outside of main campus building | Medic Hospital">
                            </div>
                        </a>
                                                                <a class="story-panel__title-link" href="/patients/international"><h3 class="story-panel__title">International Patients</h3></a>
                                                                            </div>
            </div>
        </li>
            </ul>
        </div>
        <a class="story-panel__nav-button story-panel__nav-button--prev js-story-panel__nav-button--prev story-panel__hide" style="top: 196.34px;"><img src="public/Frontend/images/icon-arrow-left.svg"></a>
        <a class="story-panel__nav-button story-panel__nav-button--next js-story-panel__nav-button--next" style="top: 196.34px;"><img src="public/Frontend/images/icon-arrow-right.svg"></a>
        <div class="story-panel__pagination js-story-panel__pagination"><span class="story-panel__pagination-dot-animation js-story-panel__pagination-dot-animation" style="left: 359px; top: 0px;"></span><a class="story-panel__pagination-dot js-story-panel__pagination-dot story-panel__pagination-dot--active js-story-panel__pagination-dot--active"></a><a class="story-panel__pagination-dot js-story-panel__pagination-dot"></a></div>
    </section>



<section class="teaser-list teaser-list--news" id="news-sectionf2a6a60279b548448bd741918c454796">
    <div class="js-ad__leaderboard ad__leaderboard">
        <div class="container">
            <div class="ad__wrapper">
                <div id="leaderboard2_728x90" class="ad-type--leaderboard ad-context--all">
                </div>
            </div>
        </div>
    </div>
   
</section>
</div>

@endsection 