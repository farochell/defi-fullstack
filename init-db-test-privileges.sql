-- init-db-test-privileges.sql
GRANT ALL PRIVILEGES ON symfony_test.* TO 'symfony'@'%';
FLUSH PRIVILEGES;