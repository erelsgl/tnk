DROP TABLE IF EXISTS mspry_psuqim;
CREATE TABLE mspry_psuqim
SELECT book_code AS sfr, chapter_letter AS prq0, count(*) AS count
FROM psuqim
GROUP BY sfr, prq0
WITH ROLLUP;

DROP TABLE IF EXISTS psuq_qodm_hba;
CREATE TABLE psuq_qodm_hba
SELECT book_code, 
chapter_letter, verse_number, 
chapter_letter as previous_chapter, verse_number-1 as previous_verse_number, 
chapter_letter as next_chapter, verse_number+1 as next_verse_number, 
count as verses_in_chapter
FROM psuqim
INNER JOIN mspry_psuqim ON(sfr=book_code AND prq0=chapter_letter)
;

UPDATE psuq_qodm_hba
INNER JOIN qodm_hba ON(next_chapter=qodm_hba.qodm)
SET 
	next_chapter=qodm_hba.hba,
	next_verse_number=1
WHERE next_verse_number>verses_in_chapter
;

UPDATE psuq_qodm_hba
INNER JOIN qodm_hba ON(previous_chapter=qodm_hba.hba)
INNER JOIN mspry_psuqim ON(sfr=book AND prq0=qodm_hba.qodm)
SET 
	previous_chapter=qodm_hba.qodm,
	previous_verse_number=count
WHERE previous_verse_number=0
AND qodm_hba.qodm <> ""
;

UPDATE psuq_qodm_hba
SET 
	previous_chapter="",
	previous_verse_number=""
WHERE previous_verse_number=0
;

ALTER TABLE psuq_qodm_hba
DROP COLUMN verses_in_chapter
;
