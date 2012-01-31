-- phpMyAdmin SQL Dump
-- version 3.4.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 18 2012 г., 02:25
-- Версия сервера: 5.1.40
-- Версия PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `granat`
--

DELIMITER $$
--
-- Функции
--
CREATE DEFINER=`granat`@`localhost` FUNCTION `getNextModelId`(
        model_id INTEGER(11)
    ) RETURNS int(11)
BEGIN
	DECLARE collection_id INTEGER;
	DECLARE next_id INTEGER;
	DECLARE first_id INTEGER;

    SELECT m.collection_id INTO collection_id FROM `gr_models` AS m 
    	WHERE m.id = model_id 
        LIMIT 1;
    
    SELECT m.id INTO next_id FROM `gr_models` AS m
    	WHERE (m.id > model_id) AND (m.`collection_id` = collection_id)
        LIMIT 1;
        
    SELECT m.id INTO first_id FROM `gr_models` AS m
    	WHERE (m.id <> model_id) AND (m.`collection_id` = collection_id)
        LIMIT 1;
    
	RETURN IFNULL(next_id, first_id);
END$$

CREATE DEFINER=`granat`@`localhost` FUNCTION `getPrevModelId`(
        model_id INTEGER(11)
    ) RETURNS int(11)
BEGIN
	DECLARE collection_id INTEGER;
	DECLARE prev_id INTEGER;
	DECLARE last_id INTEGER;

    SELECT m.collection_id INTO collection_id FROM `gr_models` AS m 
    	WHERE m.id = model_id 
        LIMIT 1;
    
    SELECT m.id INTO prev_id FROM `gr_models` AS m
    	WHERE (m.id < model_id) AND (m.`collection_id` = collection_id)
        ORDER BY m.id DESC
        LIMIT 1;
        
    SELECT m.id INTO last_id FROM `gr_models` AS m
    	WHERE (m.id <> model_id) AND (m.`collection_id` = collection_id)
        ORDER BY m.id DESC
        LIMIT 1;
    
	RETURN IFNULL(prev_id, last_id);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `gr_categories`
--

CREATE TABLE IF NOT EXISTS `gr_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `key` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `gr_categories`
--

INSERT INTO `gr_categories` (`id`, `name`, `key`) VALUES
(1, 'Открытый силуэт', 'Открытый_силуэт'),
(2, 'Закрытый силуэт', 'Закрытый_силуэт'),
(3, 'Для беременных', 'Для_беременных'),
(4, 'Для полных', 'Для_полных');

-- --------------------------------------------------------

--
-- Структура таблицы `gr_collections`
--

CREATE TABLE IF NOT EXISTS `gr_collections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `key` varchar(50) NOT NULL,
  `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `main_model_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order` (`order`),
  KEY `main_model_id` (`main_model_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `gr_collections`
--

INSERT INTO `gr_collections` (`id`, `name`, `description`, `key`, `order`, `main_model_id`) VALUES
(1, 'Rolland Emerrich', 'Здесь у нас какой-то текст про этого самого Рональда.', 'Rolland_Emerrich', 0, 6),
(2, 'Ketty Perry', NULL, 'Ketty_Perry', 0, 0),
(3, 'Trololo', NULL, 'Trololo', 0, 0),
(4, 'Kelvin Klein', NULL, 'Kelvin_Klein', 0, 0),
(5, 'Baba Paraska', NULL, 'Baba_Paraska', 0, 0),
(6, 'Product tank', 'sfdu yaoiuf podupoipiapa', 'Product_tank', 0, 0),
(7, 'Bypydipuk', 'Soukecousouc withy fyloomu nymowejou vaj qaetomoo bosol coovemy loocathea fafigabufaga wygexoomubou lasonobalea sopilemewe tyhedepi hajosok fodo pedeafou meanez cithakyruxo jude kipy toutakuv peboo toodeab boshaefrorid.', '', 0, 0),
(8, 'Shoujer', 'Nidebaeqe deloo mol cevuzafr gohevopyw shibifreatha pithuceachef syzegycath kylysovypead gacoumowuxae juryq kasoofa pimousyvoo qycucanaedu gounan cekeavanoom pofasifu fopoohykiz hyceqadix xosym shadudoteal maeqalafida teafrupenu lenea zyp qigousif qumiceaqobea.', '', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `gr_fabrics`
--

CREATE TABLE IF NOT EXISTS `gr_fabrics` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `photo` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

--
-- Дамп данных таблицы `gr_fabrics`
--

INSERT INTO `gr_fabrics` (`id`, `name`, `description`, `photo`) VALUES
(1, 'Fopofak', 'Jycuhajizuf neawosiba frea zuse chikufae zymebata hiloopyl deametheamae pechegeachyq xaeq dof frubaeche feanafr sykisae bithec gotoonalepel moonoud bep haefath docha sae piv pufretoule nouhouqe nosoulogydip goubool goozun.', 'w2YoZ.jpg'),
(2, 'Shomem', 'Jywat pipovuza zymijoth naha xeagouchusa socathoth goot loleaduthash mudegecegafe shenubo sipazeakeb chisadosety jeadudaepij vou hedogyshumo nutaerea gag gofe posi froluthouhif boferyhoupoj jajudysash.', 'QiOxh.jpg'),
(3, 'Dufroo', 'Shi dufyjoupefu goge cilac freax nopamae nagumyhis kuhooped selea wove wouqe jooq thouza lepooguly heanyfrachi catuduru hujehohagaf.', 'gJHQW.jpg'),
(4, 'Loo', 'Ximofoulet pubudidoolou caefin shihufra minuny goovoudyfaeh badosa ropypy duxevylov kesybifimifoo sha sar togogora qecukaxeci bet chavooteabae pomathae vagoo genekoorifys shufif beam thasolulaep lunoth.', 'PIrdY.jpg'),
(5, 'Nonenifesh', 'Faefa shizenooh dyzejicoukoo rogothoolyda zeanasou kyzemonon thabi pox choo paereqicif dozushou motahit fudofud mebifrupae pyva poosycoonyn hafr kifynyg qujisish lexoxesood tesypycifaw chuginofypae nataperev xanib bilesobyta gabet culael nunaejos.', '5NBGY.jpg'),
(6, 'Jookomaban', 'Gybibonoh zysicidu gopaebilakyc netoceaje seabuko duqaet winyfadou fuxus karacivoch sodatateaxo thyqaeqiguwou.', 'cafws.jpg'),
(7, 'Dunana', 'Cothatery mamae satoutu wuwyd haejasumafoo shomyfry cookucudy kivy xuzaecith soo gacodiz dipyshucis.', '1dhl8.jpg'),
(8, 'Maep', 'Coxihouv sibirytym wisylob sexacechi choo mekealaeti woofeav dyhetogecafr gog thesa taeshulecoo buhiq ripiqoo caelatufehum nax bulelysuha.', 'ru5GP.jpg'),
(9, 'Feleam', 'Haevaeshikae freloo peasacych gekefrouhae sho zealekasoxoo.', '65yDq.jpg'),
(10, 'Boopu', 'Vaethakae sool gytofopima lomikihe joo puxaeta shouli zik shaebouzab bouta kotae wapateamoh zilugig wyfapimaepa zudegeamon kychea zouwi telykixash tifae.', '4W7Px.jpg'),
(11, 'Cogufichoux', 'Xeshyqaelaev chocod fonydoofe frea chesead fanaezyshup fuh nogi.', 'f0awk.jpg'),
(12, 'Getider', 'Haejywymo caecokulicou freaf kolod shuwipy kibofr.', 'JQRSk.jpg'),
(13, 'Gatoocou', 'Cameahehae gitoumibazyb deahovotid hichyzon raechuth cheahirae xami quleateachush.', '15.jpg'),
(14, 'Kathe', 'Xyruginyn shyjafysa fyshapachae lipynynagefr zeaketougub koomogujicou cymedetosh xuzy pevyg mypin socyche.', 'BGL5f.jpg'),
(15, 'Musadaemo', 'Dae chu maekoor gyfy giheado huceman gaenae rouh goojufeasea doves caetaetozou cito caeqefr lacepoupobo nymuqaecha husugih teahoj guroumez kudaece toozef paegeanala dutadu bas.', 'ozMEe.jpg'),
(16, 'Koo', 'Qosabyjae moxeaki qeathal dishyquxusea razu kowiwaveli koolixad sexumouzybou jutypofog mofut muf kufrea diji keafr.', 'xnNRI.jpg'),
(17, 'Qeteaf', 'Casesasowo cithae doch mozypovou mutaedisil danoc foxipul gegufr muwaesoo jytas nomopetaw womegoo disigyp thuboo.', '0VdHD.jpg'),
(18, 'Cea', 'Gohy kubevi kokudo nougogyp hyjikys sekyfezy mefrooshea qaech lufreaboucad bouk lyhu.', 'FWOMB.jpg'),
(19, 'Zeaposhy', 'Chaxa byvog thypuzifug wanaevi qikousetoux tixolimea mukae xavecoovydu loocovo padytafic roojoci xipagaf jegyleashe cozafeduqi tuvapyjivac froumeci pael cumirilae losabezuhae lumynyp soupech suhiqyxu tycebutytouh fotasimu chynogafuxo bea fuduqymuci.', 'uXzyG.jpg'),
(20, 'Nixady', 'Fekeby woo voujolochelu qisuwoli dopaxyxijis mocumypifri nythe vup paehepady tybor.', 'w7iJL.jpg'),
(21, 'Pifunoosuc', 'Dygulatoocu bixo xootouhae gejoomysaly tubon louthu boofoufigiha paepohumalou tepyvaqaet fifeca.', 'NEwSo.jpg'),
(22, 'Ferabae', 'Froote chobooc towu doj pelelo.', 'DbBfg.jpg'),
(23, 'Geafalagosab', 'Zoboukefu tipexuqeaf gody boven dynuj nosae voo leanaedae foth nuzyliqun pykaeg shipealea.', 'R2XyC.jpg'),
(24, 'Cizacu', 'Pelu delasuhae qityfroupa botodyfe sifreamija kysusox cuhaeni pamycanyxeac xigeaqic woshoosuch pulae zaninae tegidootekea toreb zycypemotocoo fefafouth jit zesy fearoo wyfileveku boo nimina fucakychirox dis.', 'Uma9L.jpg'),
(25, 'Guveshefufoh', 'Fin laxy chibou goulo putonyhihewae nokounit fae foz zebiqimusamy xavewoucu jeatuqa dak leg goujorijoxae paelironea lokukoop vece hoosysych dyre gopa bosothukycea hapupeasusyv.', '0LZph.jpg'),
(26, 'Kuch', 'Sha froomarooz cytukea lyhach buvookaehefr kymaetoceqae hunithihyhea bipoloonaeke fexoupoumisa nugaekiq musycy tucidy kea lono gae mealy thifefraq sigougou thobygakybu pisyz fave temyduv fuhi lesa fougiweba wiso fufitoliryr koucivek.', 'dvzoq.jpg'),
(27, 'Culahae', 'Ceasatuma banokoon sochucegea vymyb kerekalod myfaf gekukat nifea xaelapyxak hearusu.', 'VaQ4W.jpg'),
(28, 'Wiruqig', 'Haegea faneabunuwi raekuwou luro sisifebomusou deam vogywaelegit qoomubaes ciseqokok cysinicage choruset nagookote loucijuhez fri hyb nowinuvodea leafygenoc molusaseavag.', 'zBqZT.jpg'),
(29, 'Kexyhynepyv', 'Nofo nupeb bouguwy siq freqapupot zaeveafr kaecaku pousou jemyk xigealae siridusoos qileakech riconae vugacishosae.', 'uZ9S8.jpg'),
(30, 'Fryweveach', 'Jetaefufu qovicusadufr zechoukyl nafrumafep shoot qechygea zootowuth ladikek fanutepeaso cab kekouve votiqoog sufryfen cogashaleth bacezapyloce.', 'lfL87.jpg'),
(31, 'Jenageaqodou', 'Rifry geboolo booxymae roqeazoupi faruneachou gigouqeca supouhe jaenea.', 'afJcE.jpg'),
(32, 'Shetoupocouh', 'Morun pagoolugo thaepaxeapid dalipyth gehuzireaf froutu siposhea fraebofatisoo runopuk wikoc makade fyfuc bujopyforevy.', 'DJMI9.jpg'),
(33, 'Shea', 'Gacylesh fizou vybykubajou waepagekucu frethoota paekookegyne chygysibujy hagaelyzoh pajuveajyj qinyvoobili keguce geachidagasa wibutojeaz cog thochiput.', 'ujgi8.png'),
(34, 'Napea', 'Veachi dazoxu tigekytofa degootolecoth pooxyxuthyv xoxogadagyp thoovenea tipaedishyc.', 'RSmV6.jpg'),
(35, 'Cimubaesoo', 'Hut peapofa nehyxabym tiroo fosobotyr fonohesh fufyc necoush raepaeh hecae.', 'jNsM5.jpg'),
(36, 'Qaryhewuti', 'Zinec bytemaly gofr gizyw nethos tokouti xajif zopywythu ruguboowek haefr bileavashyd cul socha fuzodae shypoucineh kizachen shucicyfoch fraqigyfea.', 'OAQxP.jpg'),
(37, 'Rookut', 'Ticoujeacy taefecanahut keb maqujyxy dipu fitythoodou sumuhod golocybae butytycum tou bea kafogomov nywijup wizak frolyb fic fifakeceatam gaxetojydatou byzea gefaedigoh fewoo seboo myfudoob thach foox wysychiw zid dataef.', 'dImCT.jpg'),
(38, 'Hany', 'Keapea pooq xyshepa daeby syshuw caeqou sous mosuv bach feakoocyqes lumum fugoo xaj vokoudinooc ceas midadaeg ceacaeponaej bath xabug nooburedicep toubinef jooqoluta nahy qaqudygyzucou befrushet qoumyz pijouxawushy ceamecumelou.', '1KZGe.jpg'),
(39, 'Hashuchaeqoo', 'Kusicovou feapipacusem frogulukaf jodigoreasoth chesagucysoo lepe jougokibuch cah moqea.', 'Ea1mF.jpg'),
(40, 'Choo', 'Semobeafea dethoogizup voomehypoz qufaexeapoub wan zek dyfab fysopigea laxi raexoo kitedub hihae kepo qosy bamaqae cizeaf.', '4hsDJ.jpg'),
(41, 'Byshoo', 'Goubaz qaezylaloo jyfr too kisaelou deafeaq feaw taelourujap noodych cuqoubouxae xoofr raec gefamifufu rugidythide lothi shyluzy zezoovooh kiko.', 'hkdv9.jpg'),
(42, 'Frooqupo', 'Raeq tygeash pucytutuch kileshyx gagootizak neat caceathewafi cid duwisufou wealanoo fric shouwyk.', 'aVL7p.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `gr_models`
--

CREATE TABLE IF NOT EXISTS `gr_models` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `key` varchar(50) NOT NULL,
  `collection_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `main_photo_id` int(11) unsigned NOT NULL,
  `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `collection_id` (`collection_id`),
  KEY `order` (`order`),
  KEY `main_photo_id` (`main_photo_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Дамп данных таблицы `gr_models`
--

INSERT INTO `gr_models` (`id`, `name`, `description`, `key`, `collection_id`, `category_id`, `main_photo_id`, `order`) VALUES
(1, 'Vujicou', 'Siriwovu sou meb rifed raecouthycoo mufodyfr pecach chapifoohyfr deaj.', 'Fyrishijae', 2, 1, 1, 0),
(2, 'Huthou', 'Rolitoorale cekexoofr paxi geaj cean jalead mywakymada gimaechu kanufr goxuthuro shoodu githal beadoruqaja saxolitudik shae nocanin ceaqusaedot.', 'Coufr', 1, 4, 4, 0),
(3, 'Zadoubynoxae', 'Kuthigaxuwi shogifr nyxirojeasoo faeh cebofage rooch raelitooth jizoofoch fraef xyqixymukihi rekouf noracufr mabasobidoof goozi qosilechu.', 'Gavougujy', 4, 4, 9, 0),
(4, 'Tefifea', 'Paehaleapaex lugos xoxymabatus xibean fuzi shefaefegah xykitolokab faloufegy bea tapaf soof dysygoudo lufralykibea neheacoo sychyqudach kysunonoogyc gouchax fusouci lythu varun xoozafrealo caheceqokifi sapefemae chuj souchako golo zedetae.', 'Byfre', 1, 1, 14, 0),
(5, 'Guvougeacyky', 'Cifoo miloloofoo wenajify duruqyke zazosuhyso namyguxena taevesilou typaefyluzyc kumoogem chonecheaf nyfygecae qisuraequke keacaecomoo netufusekes shacobylyb sofrytoxegok cous.', 'Piwo', 3, 2, 17, 0),
(6, 'Paethadily', 'Cou fricov fabyvunyqea tawudycy paefuj thoolel gyci vafobak koothoryriw gyvafrago nukeamasagae tikouqad xooreafabu hou thiwedoci frathet koniqouv geahyzufa rys wymozaegy chyjymadub modoqougeco gikexecho sufiqea tetouwagi qetahoog ceageavy.', 'Fraruro', 1, 4, 21, 0),
(7, 'Pytae', 'Vivyroothae mousealazep xead pihikeagecy ganytosookoo kygesa rafrut fepy kowix gaedoothywe dypakumuj wyleapomi pacukoot cho pigodaeput.', 'Rufr', 1, 4, 24, 0),
(8, 'Vyximooby', 'Beasuthoo thixydae hofaew laequtouf zaes xaejo domadobif bobakepana kaelofae dagothooth gygoucouzoon jeteaxytooch pyxeavafrycy faen kedaem roucinoofr themoceazidae zeagehoucyb.', 'Bigeju', 4, 2, 26, 0),
(9, 'Shygazaeshoo', 'Coocepor gaef bibijepyj hoolooqige batuhucu kupilufeapea dudishaf cofojy tidumythooxi keqeataepea secamaqofi kyqoow cykofovy.', 'Fraenuc', 2, 4, 28, 0),
(10, 'Boomoo', 'Nunip keanoofou boow nearoco jifrugeab jiwinoomafro lisidaesh batoogutish choujikuweb zyfom moupy qegegothea tynukachaew cypycu vesoufe.', 'Ketutood', 1, 2, 31, 0),
(11, 'Gobufooxyne', 'Shylocemea nifyshood freagoga kulykony dyzedimoko monula ceabotoshyw.', 'Topeazoudu', 2, 1, 36, 0),
(12, 'Mef', 'Deal wobashea qeakouf diru xafax bagof hyseth chinoofuc dycados zeficheash thoobysoodou tebisea.', 'Shys', 2, 2, 37, 0),
(13, 'Ciripumopo', 'Pubymafefea buz tae bocotaebylu wipity laxaebyzykyt vadid kaeh cux shedemapou goukyk sexeamuqoshu bimae xaesyligyw kaenaemyju xoleten boomik feakobufou geaxupyteajea nupishitaece hooj deaseqemith dicoosh zafra ronybekim pyzupofudig gixinonae hechap.', 'Davethamidich', 1, 2, 40, 0),
(14, 'Wookaekoraem', 'Bolaezodese leloto koucea fidyjynit xynegin nymyvyf thysegoolixae kuthucasypoo.', 'Kocymoqu', 4, 2, 41, 0),
(15, 'Pejochae', 'Faet mouja dushaecesha seas bor myrooleacea cec kudabech mousixydaele thofa cyvou nigipohesy symypylitoo pobae vouxubifo mujoufraeloo dyz.', 'Mush', 4, 4, 44, 0),
(16, 'Coonoohaed', 'Fogo zysh thinaes wyshyzo jasufez vaekoupe moseaco fevitu mafrekae sod qygodiz pucuganeheq vaetechiko coutagoo fachune xokugoojoojy cusaecogi.', 'Bazide', 4, 1, 45, 0),
(17, 'Kaegamo', 'Nexoozoz cac nea mycherymyloo fabodipij cumouthysh loujith faech xashoodopae motumudy.', 'Qonogegub', 3, 4, 47, 0),
(18, 'Zogyfoofe', 'Tealitoofr maek damekochy goto mileavudyth viti faf froo quchoobyq pap froujamokyb kyxyjuhyr wovucho toxesufukuc dosuhyk geaqeafape sheba dybouly deazoweko baeweah couneheanul frylythoo mos froucady sheshouroxut thychougy xifrulurouge feachaqifag.', 'Suthecy', 4, 1, 50, 0),
(19, 'Geh', 'Rou ceniboomefr cybougofr frahoowouz zymootoce vyl.', 'Byn', 3, 4, 52, 0),
(20, 'Xoo', 'Xisybea sydigox kidawaesh shootulofri filooj wego bear fosapujyn.', 'Nucif', 6, 1, 53, 0),
(21, 'Naqaerynykuf', 'Peaxomea qeditoo mothupothy tothi batykou hokefedoo pae laesoutheaf gosh toutacae pysh fycitouk niwateaj kixodoo mafoog nuvythy louzoudoog dasufehi divaecae tofoonysaequ tocanoo koum qilinafou gojaneby thuthyhufeg rutobe zae xousuw.', 'Jaeky', 1, 1, 55, 0),
(22, 'Byjurys', 'Noulezofri lagu towarefro naewok fopan govae doqyfeza hegachycich lahetyt katugacaj ruqetywoosy qumyxi jideafaelou teafrihe goowosh.', 'Cuwobifr', 6, 1, 58, 0),
(23, 'Vakouthoj', 'Hynagooz pitagewagou mutouzou qydewu neacoz lajoodalyboo frubaejute faetuli syseakimifea madae fisethashe shuqibopig mameaqaesit nudi becouvou fifroubyfil noudenukyl newiqujiv cewyni saxaenirilom rourevifanoc thepou pechaedekar vedeleafuto gubouk.', 'Xibikivipath', 4, 1, 60, 0),
(24, 'Chybuw', 'Zybymea neashate gatycil xouni luchuthudyp datowajakesh nyfypeapi qypufreab dach frou bylo racou thipafrap naesae zipochafea chuheth goody fosuqyc zyteamopiza neaqea loofre freqimyfroud gulea tipaxoumic cytea dytudiroo cutazyshofuth bousathokeas.', 'Zae', 1, 4, 61, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `gr_photos`
--

CREATE TABLE IF NOT EXISTS `gr_photos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(10) NOT NULL,
  `extension` varchar(5) NOT NULL,
  `model_id` int(11) unsigned NOT NULL,
  `order` int(3) unsigned NOT NULL DEFAULT '0',
  `is_model_title` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`),
  KEY `order` (`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- Дамп данных таблицы `gr_photos`
--

INSERT INTO `gr_photos` (`id`, `hash`, `extension`, `model_id`, `order`, `is_model_title`) VALUES
(1, 'R0l6X', 'jpg', 1, 0, 0),
(2, 'RNCE2', 'jpg', 1, 0, 0),
(3, 'PHJd0', 'jpg', 1, 0, 0),
(4, 'wsJPC', 'jpg', 2, 0, 0),
(5, 'RSlH3', 'jpg', 2, 0, 0),
(6, 'wEAga', 'jpg', 2, 0, 0),
(7, 'S5uiy', 'jpg', 3, 0, 0),
(8, 'DNfxX', 'jpg', 3, 0, 0),
(9, '8Txtl', 'jpg', 3, 0, 0),
(13, 'Svsza', 'jpg', 4, 0, 0),
(14, 'KRxVP', 'jpg', 4, 0, 0),
(15, 'yR7o5', 'jpg', 4, 0, 0),
(16, '1LlM2', 'jpg', 5, 0, 0),
(17, 'KrFhM', 'jpg', 5, 0, 0),
(18, 'kN76g', 'jpg', 5, 0, 0),
(19, '2XO50', 'jpg', 6, 0, 0),
(20, 'i9b0a', 'jpg', 6, 0, 0),
(21, 'iRqzw', 'jpg', 6, 0, 0),
(22, '8XuS2', 'jpg', 7, 0, 0),
(23, 'MGd0Z', 'jpg', 7, 0, 0),
(24, 'OYEvw', 'jpg', 7, 0, 0),
(25, 'Or2nq', 'jpg', 8, 0, 0),
(26, 'RIcof', 'jpg', 8, 0, 0),
(27, 'mtXTH', 'jpg', 8, 0, 0),
(28, '0Us7y', 'jpg', 9, 0, 0),
(29, 'eK2AR', 'jpg', 9, 0, 0),
(30, 'unSCT', 'jpg', 9, 0, 0),
(31, 'UEjOC', 'jpg', 10, 0, 0),
(32, 'cIDEw', 'jpg', 10, 0, 0),
(33, 'WKpNh', 'jpg', 10, 0, 0),
(34, 'pZh9e', 'jpg', 11, 0, 0),
(35, '2LwOu', 'jpg', 11, 0, 0),
(36, 'v1VBO', 'jpg', 11, 0, 0),
(37, 'eXylT', 'jpg', 12, 0, 0),
(38, 't5CMv', 'jpg', 12, 0, 0),
(39, 'FIvQj', 'jpg', 13, 0, 0),
(40, 'j0X7g', 'jpg', 13, 0, 0),
(41, 'guNJQ', 'jpg', 14, 0, 0),
(42, 'FClBu', 'jpg', 14, 0, 0),
(43, '8SL39', 'jpg', 15, 0, 0),
(44, 'oevHU', 'jpg', 15, 0, 0),
(45, '2SYsn', 'jpg', 16, 0, 0),
(46, 'YTLZh', 'jpg', 16, 0, 0),
(47, 'ZLGMo', 'jpg', 17, 0, 0),
(48, 'NEpoa', 'jpg', 17, 0, 0),
(49, 'lnBgS', 'jpg', 18, 0, 0),
(50, 'f53GE', 'jpg', 18, 0, 0),
(51, '1l5iL', 'jpg', 19, 0, 0),
(52, 'YaEKF', 'jpg', 19, 0, 0),
(53, '1pVWb', 'jpg', 20, 0, 0),
(54, 'U7DWX', 'jpg', 20, 0, 0),
(55, 'Z4DtO', 'jpg', 21, 0, 0),
(56, 'pyx0J', 'jpg', 21, 0, 0),
(57, 'Kr7qS', 'jpg', 22, 0, 0),
(58, 'KjX5u', 'jpg', 22, 0, 0),
(59, 'cxn2P', 'jpg', 23, 0, 0),
(60, 'd3EIJ', 'jpg', 23, 0, 0),
(61, 'T2Z1r', 'jpg', 24, 0, 0),
(62, 'b0zZs', 'jpg', 24, 0, 0),
(63, 'TIonY', 'jpg', 11, 0, 0),
(64, 'uG1fK', 'jpg', 11, 0, 0),
(65, 'Cij9O', 'jpg', 11, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `gr_users`
--

CREATE TABLE IF NOT EXISTS `gr_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `role` enum('admin','user','moderator') DEFAULT 'user',
  `date_added` datetime NOT NULL,
  `status` enum('active','banned','suspended') NOT NULL DEFAULT 'suspended',
  PRIMARY KEY (`id`),
  KEY `state` (`status`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `gr_users`
--

INSERT INTO `gr_users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `date_added`, `status`) VALUES
(1, 'Victor', 'Gryshko', 'victor@skaya.net', 'cca8dd8babd4c9996c8dfee788a49d18', 'admin', '2011-04-27 12:11:44', 'active');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `gr_models`
--
ALTER TABLE `gr_models`
  ADD CONSTRAINT `gr_models_fk` FOREIGN KEY (`collection_id`) REFERENCES `gr_collections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `gr_photos`
--
ALTER TABLE `gr_photos`
  ADD CONSTRAINT `gr_photos_fk` FOREIGN KEY (`model_id`) REFERENCES `gr_models` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
