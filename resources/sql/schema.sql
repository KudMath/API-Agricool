CREATE TABLE containers (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    name  VARCHAR NOT NULL,
    plant  VARCHAR NOT NULL,
    temperature INT NOT NULL,
    humidity INT NOT NULL,
    timeOfPlantation INT NOT NULL,
    lat FLOAT( 10, 6) NOT NULL,
    lng FLOAT( 10, 6) NOT NULL
);

insert into containers (name, plant, temperature, humidity, timeOfPlantation, lat, lng)
  values
  ('AlexandreDumas','Strawberry', 25, 50, 1488974318675, 48.8540391, 2.3916755),
  ('AlexandreDumas','Salad', 25, 50, 1488974318675, 48.8540391, 2.3916755),
  ('Bercy','Strawberry', 25, 50, 1488974318675, 48.8385379, 2.3785842);

  CREATE TABLE users (
      id          INTEGER PRIMARY KEY AUTOINCREMENT,
      username  VARCHAR NOT NULL,
      secret  VARCHAR NOT NULL,
      role VARCHAR NOT NULL,
      name VARCHAR
  );

  insert into users (username, secret, role, name)
    values
    ('pauline', 'ROLE_USER', '$2a$06$hCpAM4n7GD5pChZecVMDzOsDk3b9/QiDrkWzXnorH7YykC3ZbSfga',''),
    ('admin' ,'ROLE_ADMIN','$2a$06$hCpAM4n7GD5pChZecVMDzOsDk3b9/QiDrkWzXnorH7YykC3ZbSfga',''),
    ('container' ,'ROLE_CONTAINER','$2a$06$hCpAM4n7GD5pChZecVMDzOsDk3b9/QiDrkWzXnorH7YykC3ZbSfga', 'Bercy');
