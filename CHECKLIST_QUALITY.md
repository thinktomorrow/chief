CHECKLIST QUALITY
-----------------

##  USABILITY		
- [ ] favicon		
- [ ] Error pages: 404, 403, 500		
- [ ] Print stylesheet
- [ ] Uniforme notificatie: messages, alerts en errors op consistente wijze weergeven.
- [ ] Open Search' protocol
- [ ] Touch icons (iOS)	https://developer.apple.com/library/content/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html	
		
## PERFORMANCE		
- [ ] Versioning assets (browser cache)		
- [ ] Minify css		
- [ ] Minify javascript		
- [ ] Compress afbeeldingen	tinypng.com / https://imageoptim.com/mac
- [ ] HTML5 manifest (offline): http://diveintohtml5.info/offline.html	
- [ ] Audit pagespeed https://developers.google.com/speed/pagespeed/ , http://www.gwtproject.org/	
- [ ] Optimize image sources for retina, mobile	
- [ ] HTTP 2 protocol (vanaf 2017)
		
## CODE QUALITY		
- [ ] algemene audittool: https://auditor.raventools.com
- [ ] Controle van request headers	https://redbot.org	
- [ ] audit HTML kwaliteit	https://validator.w3.org/nu/	
- [ ] audit CSS kwaliteit	https://jigsaw.w3.org/css-validator/, http://csslint.net/
- [ ] audit form quality 	https://formlinter.com/
		
		
## SEO		
- [ ] Metatags voorzien: title en description
- [ ] rel="nofollow" Add the attribute rel='nofollow' to user-generated links to avoid spam
- [ ] Verwijder www subdomein		
- [ ] Zoek en herstel foute links	https://validator.w3.org/checklink	
- [ ] google webmasters 	https://www.google.com/webmasters/tools/home	
- [ ] Rich snippets	https://search.google.com/structured-data/testing-tool/u/0/	o.a. breadcrumbs, events
- [ ] robots.txt		
- [ ] XML sitemap	https://www.xml-sitemaps.com/	
- [ ] Integreer page analytics (Enkel voor productie omgeving activeren)
- [ ] Event tracking voor belangrijke in- en outbound links		
		
		
## SECURITY		
- [ ] CSRF bescherming		
- [ ] hashed passwords		
- [ ] SSL (vanaf 2017, nodig voor http2)
		
## SEMANTICS		
- [ ] Microdata	https://raventools.com/site-auditor/seo-guide/schema-structured-data/	
- [ ] Facebook: https://developers.facebook.com/tools/debug/		
- [ ] Twitter: https://cards-dev.twitter.com/validator		
- [ ] Google: https://search.google.com/structured-data/testing-tool/u/0/		
- [ ] General: https://www.w3.org/2003/12/semantic-extractor.html		
		
		
## ACCESSIBILITY		
- [ ] Correcte input types voor formulieren		email, url, phone, date,... (mobile friendly)
- [ ] Altijd labels gebruiken voor formvelden		
- [ ] Audit	https://accessibility.oit.ncsu.edu/sortsite/	
- [ ] Audit with screen reader	http://www.nvaccess.org/	
- [ ] Test using text only/voice only browsers
		

## COMPATIBILITY 
Controleer of de applicatie naar behoren werkt op mobiele toestellen en browsers.

- [ ] mobile audit: https://validator.w3.org/mobile-alpha/
- [ ] Browser compatibility voor Chrome on windows
                                 Firefox on mac
                                 Firefox on windows
                                 Safari on mac
                                 Internet Explorer 11+
                                 Android Browser
                                 iOS Safari
                                 iOS chrome
- [ ] Device compatibility voor iPhone 5 +
                                Google Nexus 6
                                Samsung Galaxy S5
                                iPad 4+
                                Google Nexus 7 (tablet)
                                Nokia Lumia 520 (windows)
                                Samsung Galaxy 57
                                Samsung Galaxy Note 3
                                Sony Xperia Z
                                Blackberry Z10
                                Windows Surface RT2
