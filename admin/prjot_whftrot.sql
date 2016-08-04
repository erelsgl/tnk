/* 
	PRJOT
*/






/*
UPDATE qjr_tnk1_psuq q1
LEFT JOIN qjr_tnk1_psuq q2 ON (q2.sug='הפטרה ס' AND q2.sdr=q1.sdr)
SET q1.sug='הפטרה',
q1.kotrt = replace(q1.kotrt, " אשכנז", "")
WHERE q2.sug IS NULL
AND q1.sug='הפטרה א'
*/



DROP TABLE IF EXISTS prjot;
CREATE TABLE prjot (
	qod_sfr_2 char(2),
	kotrt_sfr varchar(15),
	ktovt_prq0 varchar(16),
	mspr_prq0 smallint,
	mspr_psuq0 smallint,
	mspr_prq1 smallint,
	mspr_psuq1 smallint,
	kotrt varchar(160),
	sug varchar(15),
	sdr tinyint
) CHARACTER SET utf8;

INSERT INTO prjot
SELECT 
	p0.qod_sfr_2 AS qod_sfr_2,
	p0.kotrt_sfr AS kotrt_sfr,
	p0.ktovt AS ktovt_prq0,
	p0.mspr_prq AS mspr_prq0,
	qjr_tnk1_psuq.psuq0 AS mspr_psuq0,
	p1.mspr_prq AS mspr_prq1,
	qjr_tnk1_psuq.psuq1 AS mspr_psuq1,
	qjr_tnk1_psuq.kotrt,
	qjr_tnk1_psuq.sug,
	qjr_tnk1_psuq.sdr
FROM qjr_tnk1_psuq 
INNER JOIN sfrim_prqim p0 ON(
	qjr_tnk1_psuq.sfr = p0.qod_sfr AND
	qjr_tnk1_psuq.prq0 = p0.kotrt_prq)
LEFT JOIN sfrim_prqim p1 ON(
	qjr_tnk1_psuq.sfr = p1.qod_sfr AND
	qjr_tnk1_psuq.prq1 = p1.kotrt_prq)
;


UPDATE prjot
SET mspr_psuq0=0
WHERE mspr_psuq0=1;

ALTER TABLE prjot
ORDER BY sug, qod_sfr_2, mspr_prq0, mspr_psuq0;

CREATE TEMPORARY TABLE prjot2
SELECT p0.*,
	1 + (
	SELECT COUNT(*) FROM prjot p1
	WHERE p1.sug = p0.sug
	AND   p1.kotrt_sfr = p0.kotrt_sfr
	AND (p1.mspr_prq0 < p0.mspr_prq0 OR (p1.mspr_prq0 = p0.mspr_prq0 AND p1.mspr_psuq0 < p0.mspr_psuq0) )) AS sdr_xdj
	FROM prjot p0;

UPDATE prjot p1, prjot2 p2
SET 
	p1.mspr_prq1 = p2.mspr_prq0,
	p1.mspr_psuq1 = p2.mspr_psuq0
WHERE p1.mspr_prq1 IS NULL
AND   p1.kotrt_sfr = p2.kotrt_sfr
AND   p1.sug = p2.sug
AND (p1.mspr_prq0 < p2.mspr_prq0 OR (p1.mspr_prq0 = p2.mspr_prq0 AND p1.mspr_psuq0<p2.mspr_psuq0));

UPDATE prjot p1, prjot2 p2
SET 
	p1.sdr = p2.sdr_xdj
WHERE p1.kotrt = p2.kotrt
AND   p1.sdr=0;


UPDATE prjot p1
INNER JOIN sfrim ON(sfrim.kotrt=p1.kotrt_sfr)
SET p1.mspr_prq1  = 1 + sfrim.kmut_prqim
WHERE (p1.mspr_prq1 IS NULL);





UPDATE prjot p1
SET 
	p1.mspr_psuq1 = 0
WHERE p1.mspr_psuq1  =-1;









DROP TABLE IF EXISTS qjrim_sfrim_prjot;
CREATE TABLE qjrim_sfrim_prjot
SELECT 
	kotrt_sfr AS av,
	kotrt AS bn,
	IF(sug='פרשה', sdr, 50+sdr) as sdr_bn,
	0 as sdr_av,
	sug,
	kotrt,
	convert(CONCAT("tnk1/",ktovt_prq0,"#",mspr_psuq0) using utf8) as ktovt,
	"" as m,
	"" as l
FROM prjot
ORDER BY qod_sfr_2, kotrt_sfr, mspr_prq0, mspr_psuq0;


DROP TABLE IF EXISTS qjrim_prjot_prqim_rijonim;
CREATE TABLE qjrim_prjot_prqim_rijonim
SELECT 
	prjot.kotrt AS av,
	sfrim_prqim.kotrt AS bn,
	1 as sdr_bn,
	0 as sdr_av,
	"פרק" as sug,
	convert(IF(prjot.mspr_psuq0>0, 
		CONCAT(CONVERT(kotrt_prq USING utf8),mspr_psuq0),
		CONVERT(kotrt_prq USING utf8)) using utf8) as kotrt,
	convert(IF(prjot.mspr_psuq0>0, 
		CONCAT("tnk1/",sfrim_prqim.ktovt,'#',mspr_psuq0),
		CONCAT("tnk1/",sfrim_prqim.ktovt)) using utf8) as ktovt,
	"" as m,
	"" as l
FROM prjot, sfrim_prqim
WHERE prjot.kotrt_sfr = sfrim_prqim.kotrt_sfr
AND sfrim_prqim.mspr_prq<prjot.mspr_prq1
AND prjot.mspr_prq0 = sfrim_prqim.mspr_prq
ORDER BY sfrim_prqim.qod_sfr_2, sfrim_prqim.mspr_prq, mspr_psuq0;

DROP TABLE IF EXISTS qjrim_prjot_prqim_emcaiim;
CREATE TABLE qjrim_prjot_prqim_emcaiim
SELECT 
	prjot.kotrt AS av,
	sfrim_prqim.kotrt AS bn,
	CEILING((mspr_prq-mspr_prq0+1)/2) as sdr_bn,
	0 as sdr_av,
	"פרק" as sug,
	kotrt_prq as kotrt,
	convert(CONCAT("tnk1/",sfrim_prqim.ktovt) using utf8) as ktovt,
	"" as m,
	"" as l
FROM prjot, sfrim_prqim
WHERE prjot.kotrt_sfr = sfrim_prqim.kotrt_sfr
AND (prjot.mspr_prq0<sfrim_prqim.mspr_prq AND sfrim_prqim.mspr_prq<prjot.mspr_prq1)
ORDER BY sfrim_prqim.qod_sfr_2, sfrim_prqim.mspr_prq, mspr_psuq0;

DROP TABLE IF EXISTS qjrim_prjot_prqim_axronim;
CREATE TABLE qjrim_prjot_prqim_axronim
SELECT 
	prjot.kotrt AS av,
	sfrim_prqim.kotrt AS bn,
	49 as sdr_bn,
	0 as sdr_av,
	"פרק" as sug,
	convert(CONCAT(CONVERT(kotrt_prq USING utf8),
		IF(mspr_psuq1-1>1,'1-',''),
		(mspr_psuq1-1)) using utf8) as kotrt,
	convert(CONCAT("tnk1/",sfrim_prqim.ktovt) using utf8) as ktovt,
	"" as m,
	"" as l
FROM prjot, sfrim_prqim
WHERE prjot.kotrt_sfr = sfrim_prqim.kotrt_sfr
AND (prjot.mspr_prq1=sfrim_prqim.mspr_prq AND prjot.mspr_psuq1>0)
ORDER BY sfrim_prqim.qod_sfr_2, sfrim_prqim.mspr_prq;






DROP TABLE IF EXISTS prjot_whftrot;
CREATE TABLE prjot_whftrot (
	kotrt_sfr varchar(15),
	prq0 varchar(3),
	psuq0 varchar(3),
	prq1 varchar(3),
	psuq1 varchar(3),
	kotrt varchar(31),
	kotrt_kllit varchar(31),
	sug varchar(5),
	tokn text DEFAULT NULL
)
CHARACTER SET hebrew;

INSERT INTO prjot_whftrot (
	kotrt_sfr,
	prq0,
	psuq0,
	prq1,
	psuq1,
	kotrt,
	sug)
SELECT 
	kotrt_sfr,
	mspr_prq0,
	IF(mspr_psuq0=0,1,mspr_psuq0) AS mspr_psuq0,
	mspr_prq1,
	IF(mspr_psuq1=0,1,mspr_psuq1)-1 AS mspr_psuq1,
	kotrt,
	'פרשה'
FROM prjot
WHERE sug like 'פרשה';

INSERT INTO prjot_whftrot (
	kotrt_sfr,
	prq0,
	psuq0,
	prq1,
	psuq1,
	kotrt,
	sug)
SELECT 
	kotrt_sfr,
	mspr_prq0,
	IF(mspr_psuq0=0,1,mspr_psuq0) AS mspr_psuq0,
	mspr_prq1,
	IF(mspr_psuq1=0,1,mspr_psuq1)-1 AS mspr_psuq1,
	kotrt,
	'הפטרה'
FROM prjot
WHERE sug like 'הפטרה%';

INSERT INTO prjot_whftrot (
	kotrt_sfr,
	prq0,
	psuq0,
	prq1,
	psuq1,
	kotrt,
	sug)
SELECT 
	kotrt_sfr,
	mspr_prq0,
	IF(mspr_psuq0=0,1,mspr_psuq0) AS mspr_psuq0,
	mspr_prq1,
	IF(mspr_psuq1=0,1,mspr_psuq1)-1 AS mspr_psuq1,
	kotrt,
	'קריאה'
FROM prjot
WHERE sug = 'קריאה';



UPDATE prjot_whftrot 
INNER JOIN prqim ON (prq0=prqim.mspr)
SET prq0=prqim.kotrt;

UPDATE prjot_whftrot 
INNER JOIN prqim ON (psuq0=prqim.mspr)
SET psuq0=prqim.kotrt;

UPDATE prjot_whftrot 
INNER JOIN prqim ON (prq1=prqim.mspr)
SET prq1=prqim.kotrt
WHERE psuq1<>0;

UPDATE prjot_whftrot 
INNER JOIN prqim ON (prq1-1=prqim.mspr)
SET prq1=prqim.kotrt
WHERE psuq1=0;

UPDATE prjot_whftrot 
INNER JOIN prqim ON (psuq1=prqim.mspr)
SET psuq1=prqim.kotrt;


UPDATE prjot_whftrot
SET tokn=CONCAT(
		CONCAT("{{דף פרשה|",
			kotrt_sfr, "|",
			prq0     , "|",
			psuq0     , "|"),
		IF(psuq1='0',
			CONCAT("פרק ",
				prq1     , "|"),
			CONCAT(
				prq1     , "|",
				psuq1          )),
			"|",
			REPLACE(kotrt, "פרשת ",""),
			"}}")
WHERE sug='פרשה' 
AND kotrt_sfr IN (
	'בראשית',
	'שמות',
	'ויקרא',
	'במדבר',
	'דברים');

UPDATE prjot_whftrot
SET tokn=CONCAT(
		CONCAT("{{דף קטע|",
			kotrt_sfr, "|",
			prq0     , "|",
			psuq0     , "|"),
		IF(psuq1='0',
			CONCAT("פרק ",
				prq1     , "|"),
			CONCAT(
				prq1     , "|",
				psuq1          )),
			"|",
			kotrt,
			"}}")
WHERE tokn IS NULL;


UPDATE prjot_whftrot
SET kotrt_kllit = kotrt;

UPDATE prjot_whftrot
SET kotrt_kllit = REPLACE(REPLACE(kotrt_kllit," ספרד",""), " אשכנז","");

UPDATE prjot_whftrot
SET kotrt_kllit = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(kotrt_kllit," ג "," ")," ד "," ")," ה "," ")," ו "," ")," ז "," ")," ח "," ")," א "," "), " ב "," ");

UPDATE prjot_whftrot
SET kotrt_kllit = REPLACE(REPLACE(kotrt_kllit," מנחה",""), " שחרית","");

ALTER TABLE prjot_whftrot
ADD KEY(kotrt),
ADD KEY(kotrt_kllit);



DROP TABLE IF EXISTS prjot_whftrot_wikia;
CREATE TABLE prjot_whftrot_wikia
SELECT 
	CONCAT("קטגוריה:",kotrt_kllit) AS kotrt,
	CONCAT(
		GROUP_CONCAT(tokn ORDER BY kotrt SEPARATOR "\n\n"),
		"\n[[קטגוריה:",
		sug,
		"|",
		REPLACE(REPLACE(kotrt_kllit, "פרשת ", ""), "הפטרת ", ""),
		"]]")
	AS tokn
FROM prjot_whftrot
GROUP BY kotrt;

INSERT INTO prjot_whftrot_wikia
SELECT 
	kotrt_kllit,
	CONCAT(
		"#REDIRECT [[:קטגוריה:",
		kotrt_kllit,
		"]]")
FROM prjot_whftrot
GROUP BY kotrt_kllit
;

UPDATE prjot_whftrot_wikia p1, prjot_whftrot p2
SET p1.tokn = CONCAT(p1.tokn,
	"\n[[",
	REPLACE(p1.kotrt, "הפטרת ", "פרשת "),
	"]]")
WHERE p1.kotrt = CONCAT("קטגוריה:הפטרת ", 
	REPLACE(p2.kotrt_kllit, "פרשת ", ""))
;

DROP TABLE IF EXISTS prjot_whftrot_wikia_upload;
CREATE TABLE prjot_whftrot_wikia_upload
SELECT CONCAT(
		"#####", kotrt, "\n",
		tokn, "\n",
		"ENDOFFILE"
		)
	AS tokn
FROM prjot_whftrot_wikia;


DROP TABLE IF EXISTS sfrim_prqim;
CREATE TABLE sfrim_prqim
SELECT 
	sfrim.qod   AS qod_sfr,
	sfrim.kotrt AS kotrt_sfr,
	"פרק" as sug,
	sfrim.qod_mamre AS qod_sfr_2,
	sfrim.kmut_prqim,
	prqim.mspr AS mspr_prq,
	prqim.kotrt AS kotrt_prq,
	CONCAT(sfrim.qod_mlbim,'/',sfrim.qod_mlbim,'-',prqim.qod_mlbim) AS qod,
	CONCAT(sfrim.kotrt,' ',prqim.kotrt) AS kotrt,
	CONCAT('prqim/t', sfrim.qod_mamre,prqim.qod_mlbim,'.htm') AS ktovt
FROM sfrim
INNER JOIN prqim
WHERE prqim.mspr<=sfrim.kmut_prqim;








/* prjot_jvua */

DROP TABLE IF EXISTS prjot_jvua;
CREATE TABLE prjot_jvua (
	xtiva varchar(20),
	sdr int,
	kotrt varchar(255),
	ktovt varchar(255)
) CHARACTER SET utf8;

SET @sdr=0;
INSERT INTO prjot_jvua
SELECT "תורה",
	@sdr:=@sdr+1, kotrt, CONCAT(ktovt_prq0,'#',mspr_psuq0)
FROM prjot
WHERE sug='פרשה'
AND qod_sfr_2 BETWEEN '01' AND '05'
ORDER BY qod_sfr_2, kotrt_sfr, sdr;

SET @sdr=0;
INSERT INTO prjot_jvua
SELECT "נביאים ראשונים",
	@sdr:=@sdr+1, kotrt, CONCAT(ktovt_prq0,'#',mspr_psuq0)
FROM prjot
WHERE sug='פרשה'
AND qod_sfr_2 BETWEEN '06' AND '09'
ORDER BY qod_sfr_2, kotrt_sfr, sdr;

SET @sdr=0;
INSERT INTO prjot_jvua
SELECT "ישעיהו-ירמיהו",
	@sdr:=@sdr+1, kotrt, CONCAT(ktovt_prq0,'#',mspr_psuq0)
FROM prjot
WHERE sug='פרשה'
AND qod_sfr_2 BETWEEN '10' AND '11'
ORDER BY qod_sfr_2, kotrt_sfr, sdr;

SET @sdr=0;
INSERT INTO prjot_jvua
SELECT "יחזקאל-תרי עשר",
	@sdr:=@sdr+1, kotrt, CONCAT(ktovt_prq0,'#',mspr_psuq0)
FROM prjot
WHERE sug='פרשה'
AND qod_sfr_2 BETWEEN '12' AND '24'
ORDER BY qod_sfr_2, kotrt_sfr, sdr;

SET @sdr=0;
INSERT INTO prjot_jvua
SELECT "כתובים שיריים",
	@sdr:=@sdr+1, kotrt, CONCAT(ktovt_prq0,'#',mspr_psuq0)
FROM prjot
WHERE sug='פרשה'
AND kotrt_sfr IN ('תהלים','שיר השירים','איוב','איכה','משלי','קהלת')
ORDER BY 
kotrt_sfr='תהלים' DESC,
kotrt_sfr='שיר השירים' DESC,
kotrt_sfr='איוב' DESC,
kotrt_sfr='איכה' DESC,
kotrt_sfr='משלי' DESC,
kotrt_sfr='קהלת' DESC,
sdr;

SET @sdr=0;
INSERT INTO prjot_jvua
SELECT "כתובים סיפוריים",
	@sdr:=@sdr+1, kotrt, CONCAT(ktovt_prq0,'#',mspr_psuq0)
FROM prjot
WHERE sug='פרשה'
AND kotrt_sfr IN ('דברי הימים א', 'דברי הימים ב', 'רות', 'דניאל', 'עזרא', 'נחמיה', 'אסתר')
ORDER BY 
kotrt_sfr='דברי הימים א' DESC,
kotrt_sfr='דברי הימים ב' DESC, 
kotrt_sfr='רות' DESC, 
kotrt_sfr='דניאל' DESC, 
kotrt_sfr='עזרא' DESC, 
kotrt_sfr='נחמיה' DESC, 
kotrt_sfr='אסתר' DESC,
sdr;

INSERT IGNORE INTO prjot_jvua
SELECT * FROM prjot_xgim
;

ALTER TABLE prjot_jvua ADD PRIMARY KEY(xtiva,sdr);





DROP TABLE IF EXISTS prjot_jvua_html;
CREATE TABLE prjot_jvua_html (
	sdr int,
	html text
) CHARACTER SET utf8;

INSERT INTO prjot_jvua_html
SELECT sdr,
GROUP_CONCAT(
CONCAT("<li><b>ב",xtiva,"</b>: ",
"<a href='",ktovt,"'>",kotrt,"</a></li>
")
ORDER BY xtiva DESC
SEPARATOR ' '
)
FROM prjot_jvua
GROUP BY sdr;

ALTER TABLE prjot_jvua_html ADD PRIMARY KEY(sdr);
