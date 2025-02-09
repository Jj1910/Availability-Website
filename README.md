# Availability-Website

## Instructions

The pre-requisites are that you have a device with docker installed and can run a docker compose file from the given directory. 

### Clone Repo

```
git clone https://github.com/Jj1910/Availability-Website.git
```

### Run Docker Container

```
docker compose up -d
```

### Configure Database

```
docker exec -it AvailabilityDB /bin/bash
mysql -u ${MYSQL_USER} -p${MYSQL_PASSWORD}

mysql->use ${MYSQL_DB};

mysql->CREATE TABLE users (
	id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    pwd VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	is_admin TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
);

mysql->CREATE TABLE availability (
    username VARCHAR(50) NOT NULL,
	mondayStartTime VARCHAR(50) DEFAULT NULL,
	mondayEndTime VARCHAR(50) DEFAULT NULL,
	tuesdayStartTime VARCHAR(50) DEFAULT NULL,
	tuesdayEndTime VARCHAR(50) DEFAULT NULL,
	wednesdayStartTime VARCHAR(50) DEFAULT NULL,
	wednesdayEndTime VARCHAR(50) DEFAULT NULL,
	thursdayStartTime VARCHAR(50) DEFAULT NULL,
	thursdayEndTime VARCHAR(50) DEFAULT NULL,
	fridayStartTime VARCHAR(50) DEFAULT NULL,
	fridayEndTime VARCHAR(50) DEFAULT NULL,
    user_id INT(11),
    PRIMARY KEY (username),
	FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

INSERT INTO users (username, pwd, email, is_admin) VALUES ('admin', '${HASHED PASSWORD}', 'admin@admin.com', 1);
```

### Go to http://localhost to see the website.
