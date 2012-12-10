-- update default email if still set to old value
update ##customer set `email`='me@email.com' where `id`=1 and md5(`email`)='554924befa222bd5b407eca41b015d8e';
update ##settings set `webmaster_mail`='me@email.com' where md5(`webmaster_mail`)='554924befa222bd5b407eca41b015d8e';
update ##settings set `sales_mail`='me@email.com' where md5(`sales_mail`)='554924befa222bd5b407eca41b015d8e';

