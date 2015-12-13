select A.roomNum, A.dorm, A.sectionID
from Available A, (
select ID, dorm, sectionName,
count(case when year=2017 then 1 end) as sameGrade,
count(case when year<>2017 then 1 end) as diffGrade
from (select R.name, R.dorm, R.roomNum, S.ID, S.name as sectionName, year
  from Resident R, (
    select R.roomNum, S.ID, S.name, S.dorm 
    from Room R, Section S 
    where S.ID=R.sectionID) S
  where R.roomNum=S.roomNum
  and R.dorm=S.dorm
  ) S
group by S.ID 
having sameGrade > diffGrade
) P
where P.ID = A.sectionID
and A.dorm = 'Fisher';
