DROP DATABASE IF EXISTS ieti_tinder;
CREATE DATABASE ieti_tinder;
USE ieti_tinder;

CREATE TABLE users (
    email_user VARCHAR(255) PRIMARY KEY,
    password_user VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    surnames VARCHAR(255) NOT NULL,
    alias VARCHAR(100) NOT NULL,
    birthday DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    sex ENUM('M', 'F', 'NB') NOT NULL,
    sex_orientation VARCHAR(50) NOT NULL,
    account_status ENUM('active', 'to verify', 'inactive') NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user' NOT NULL,
    points INT DEFAULT 0 NOT NULL
);

CREATE TABLE pictures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email_user VARCHAR(255),
    path VARCHAR(255) NOT NULL,
    FOREIGN KEY (email_user) REFERENCES users(email_user) ON DELETE CASCADE
);

CREATE TABLE interactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user VARCHAR(255),
    id_receptor VARCHAR(255),
    like_user BOOLEAN NULL,
    like_receptor BOOLEAN NULL,
    date DATETIME,
    FOREIGN KEY (id_user) REFERENCES users(email_user) ON DELETE CASCADE,
    FOREIGN KEY (id_receptor) REFERENCES users(email_user) ON DELETE CASCADE
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user VARCHAR(255),
    id_receptor VARCHAR(255),
    message_user TEXT,
    date DATETIME,
    FOREIGN KEY (id_user) REFERENCES users(email_user) ON DELETE CASCADE,
    FOREIGN KEY (id_receptor) REFERENCES users(email_user) ON DELETE CASCADE
);

/*Insert 40 users to users table*/

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('jgarcia@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Joan', 'Garcia Lopez', 'jgarcia', '1990-05-14', '41.38879 2.15899', 'M', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('mmartinez@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Maria', 'Martinez Ruiz', 'mmartinez', '1985-03-22', '40.41678 -3.70379', 'F', 'homosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('asanchez@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Alex', 'Sanchez Gomez', 'asanchez', '1992-11-02', '37.77493 -122.41942', 'NB', 'bisexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('slopez@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Sofia', 'Lopez Gonzalez', 'slopez', '1989-07-15', '48.85661 2.35222', 'F', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('dhernandez@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'David', 'Hernandez Perez', 'dhernandez', '1995-12-03', '34.05223 -118.24368', 'M', 'bisexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('ltorres@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Lucia', 'Torres Ortega', 'ltorres', '1998-04-09', '51.5074 -0.1278', 'F', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('cramirez@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Carlos', 'Ramirez Vega', 'cramirez', '1991-01-19', '40.73061 -73.935242', 'M', 'homosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('egutierrez@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Emma', 'Gutierrez Flores', 'egutierrez', '1987-08-23', '35.6895 139.69171', 'F', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('jdiaz@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Javier', 'Diaz Alvarez', 'jdiaz', '1993-06-30', '19.432608 -99.133209', 'M', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('pmoreno@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Paula', 'Moreno Jimenez', 'pmoreno', '2000-02-17', '52.520007 13.404954', 'F', 'homosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('inavarro@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Isabel', 'Navarro Suarez', 'inavarro', '1982-09-05', '59.329323 18.068581', 'F', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('vcruz@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Victor', 'Cruz Romero', 'vcruz', '1997-10-12', '-33.44889 -70.669265', 'M', 'bisexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('lvargas@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Laura', 'Vargas Castro', 'lvargas', '1994-12-21', '55.755825 37.617298', 'F', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('dserrano@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Diego', 'Serrano Medina', 'dserrano', '1988-01-08', '-23.55052 -46.633308', 'M', 'homosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('creyes@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Claudia', 'Reyes Ortiz', 'creyes', '1996-05-16', '28.613939 77.209021', 'F', 'bisexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('malonso@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Marc', 'Alonso Gomez', 'malonso', '1990-11-20', '39.46991 -0.376288', 'M', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('egil@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Eva', 'Gil Fernandez', 'egil', '1999-03-02', '50.85034 4.35171', 'F', 'homosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('omolina@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Oscar', 'Molina Ramos', 'omolina', '1985-07-11', '-34.603684 -58.381559', 'M', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('mherrera@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Marta', 'Herrera Ruiz', 'mherrera', '1991-06-25', '43.65107 -79.347015', 'F', 'bisexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('acortes@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Adrian', 'Cortes Sanchez', 'acortes', '1994-09-18', '31.230391 121.473701', 'M', 'homosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('ablanco@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Alicia', 'Blanco Lopez', 'ablanco', '1992-02-14', '13.756331 100.501762', 'F', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('fortiz@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Fernando', 'Ortiz Martinez', 'fortiz', '1983-03-28', '55.953251 -3.188267', 'M', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('pcastro@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Patricia', 'Castro Ruiz', 'pcastro', '1995-08-06', '37.98381 23.727539', 'F', 'homosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('hmarti@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Hugo', 'Marti Perez', 'hmarti', '1990-05-22', '60.169856 24.938379', 'M', 'bisexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('apascual@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Angela', 'Pascual Romero', 'apascual', '1998-07-01', '41.385064 2.173404', 'F', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('mfuentes@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Manuel', 'Fuentes Diaz', 'mfuentes', '1993-10-09', '35.689487 51.389549', 'M', 'homosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('eperez@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Elena', 'Perez Vega', 'eperez', '1987-04-14', '40.712776 -74.005974', 'F', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('llozano@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Luis', 'Lozano Hernandez', 'llozano', '1991-12-25', '14.599512 120.984222', 'M', 'bisexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('nortega@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Natalia', 'Ortega Campos', 'nortega', '1999-01-11', '19.07609 72.877426', 'F', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('rvidal@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Raul', 'Vidal Salas', 'rvidal', '1986-11-04', '25.204849 55.270783', 'M', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('clopez@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Cristina', 'Lopez Torres', 'clopez', '1994-02-23', '35.689487 139.691711', 'F', 'homosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('pramirez@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Pablo', 'Ramirez Diaz', 'pramirez', '1988-06-19', '-22.906847 -43.172897', 'M', 'bisexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('cmarin@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Clara', 'Marin Gonzalez', 'cmarin', '1992-03-14', '43.662892 -79.395656', 'F', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('jnavarro@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Jorge', 'Navarro Perez', 'jnavarro', '1989-10-26', '48.856613 2.352222', 'M', 'homosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('asuarez@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Ana', 'Suarez Moreno', 'asuarez', '1997-05-12', '55.676098 12.568337', 'F', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('rcastillo@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Ruben', 'Castillo Sanchez', 'rcastillo', '1995-08-08', '40.416775 -3.70379', 'M', 'bisexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('cvega@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Carlos', 'Vega Garcia', 'cvega', '1990-05-15', '40.416775 -3.703790', 'M', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('dreyes@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Diana', 'Reyes Alvarez', 'dreyes', '1988-11-22', '19.432608 -99.133209', 'F', 'homosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('mgomez@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Manuel', 'Gomez Zamora', 'mgomez', '1992-03-10', '41.385064 2.173404', 'M', 'bisexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status) VALUES
('msanchez@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Matias', 'Sanchez Rivera', 'msanchez', '1996-01-11', '19.07609 72.877426', 'M', 'heterosexual', 'active');

INSERT INTO users (email_user, password_user, name, surnames, alias, birthday, location, sex, sex_orientation, account_status, role) VALUES
('admin@ieti.site', 'b251f16692c49c63e2e6668044b82f299a76d94e476b1131bbe05444b0ede6b1', 'Matias', 'Sanchez Rivera', 'msanchez', '1996-01-11', '19.07609 72.877426', 'M', 'heterosexual', 'active', 'admin');


/*Insert 80 pictures to pictures table*/

INSERT INTO pictures (email_user, path) VALUES
('jgarcia@ieti.site', '/profilePictures/jgarcia1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('jgarcia@ieti.site', '/profilePictures/jgarcia2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('mmartinez@ieti.site', '/profilePictures/mmartinez1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('mmartinez@ieti.site', '/profilePictures/mmartinez2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('asanchez@ieti.site', '/profilePictures/asanchez1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('asanchez@ieti.site', '/profilePictures/asanchez2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('slopez@ieti.site', '/profilePictures/slopez1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('slopez@ieti.site', '/profilePictures/slopez2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('dhernandez@ieti.site', '/profilePictures/dhernandez1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('dhernandez@ieti.site', '/profilePictures/dhernandez2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('ltorres@ieti.site', '/profilePictures/ltorres1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('ltorres@ieti.site', '/profilePictures/ltorres2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('cramirez@ieti.site', '/profilePictures/cramirez1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('cramirez@ieti.site', '/profilePictures/cramirez2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('egutierrez@ieti.site', '/profilePictures/egutierrez1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('egutierrez@ieti.site', '/profilePictures/egutierrez2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('jdiaz@ieti.site', '/profilePictures/jdiaz1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('jdiaz@ieti.site', '/profilePictures/jdiaz2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('pmoreno@ieti.site', '/profilePictures/pmoreno1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('pmoreno@ieti.site', '/profilePictures/pmoreno2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('inavarro@ieti.site', '/profilePictures/inavarro1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('inavarro@ieti.site', '/profilePictures/inavarro2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('vcruz@ieti.site', '/profilePictures/vcruz1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('vcruz@ieti.site', '/profilePictures/vcruz2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('lvargas@ieti.site', '/profilePictures/lvargas1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('lvargas@ieti.site', '/profilePictures/lvargas2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('dserrano@ieti.site', '/profilePictures/dserrano1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('dserrano@ieti.site', '/profilePictures/dserrano2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('creyes@ieti.site', '/profilePictures/creyes1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('creyes@ieti.site', '/profilePictures/creyes2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('malonso@ieti.site', '/profilePictures/malonso1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('malonso@ieti.site', '/profilePictures/malonso2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('egil@ieti.site', '/profilePictures/egil1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('egil@ieti.site', '/profilePictures/egil2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('omolina@ieti.site', '/profilePictures/omolina1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('omolina@ieti.site', '/profilePictures/omolina2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('mherrera@ieti.site', '/profilePictures/mherrera1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('mherrera@ieti.site', '/profilePictures/mherrera2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('acortes@ieti.site', '/profilePictures/acortes1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('acortes@ieti.site', '/profilePictures/acortes2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('ablanco@ieti.site', '/profilePictures/ablanco1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('ablanco@ieti.site', '/profilePictures/ablanco2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('fortiz@ieti.site', '/profilePictures/fortiz1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('fortiz@ieti.site', '/profilePictures/fortiz2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('pcastro@ieti.site', '/profilePictures/pcastro1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('pcastro@ieti.site', '/profilePictures/pcastro2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('hmarti@ieti.site', '/profilePictures/hmarti1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('hmarti@ieti.site', '/profilePictures/hmarti2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('apascual@ieti.site', '/profilePictures/apascual1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('apascual@ieti.site', '/profilePictures/apascual2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('mfuentes@ieti.site', '/profilePictures/mfuentes1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('mfuentes@ieti.site', '/profilePictures/mfuentes2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('eperez@ieti.site', '/profilePictures/eperez1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('eperez@ieti.site', '/profilePictures/eperez2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('llozano@ieti.site', '/profilePictures/llozano1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('llozano@ieti.site', '/profilePictures/llozano2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('nortega@ieti.site', '/profilePictures/nortega1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('nortega@ieti.site', '/profilePictures/nortega2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('rvidal@ieti.site', '/profilePictures/rvidal1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('rvidal@ieti.site', '/profilePictures/rvidal2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('clopez@ieti.site', '/profilePictures/clopez1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('clopez@ieti.site', '/profilePictures/clopez2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('pramirez@ieti.site', '/profilePictures/pramirez1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('pramirez@ieti.site', '/profilePictures/pramirez2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('cmarin@ieti.site', '/profilePictures/cmarin1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('cmarin@ieti.site', '/profilePictures/cmarin2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('jnavarro@ieti.site', '/profilePictures/jnavarro1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('jnavarro@ieti.site', '/profilePictures/jnavarro2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('asuarez@ieti.site', '/profilePictures/asuarez1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('asuarez@ieti.site', '/profilePictures/asuarez2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('rcastillo@ieti.site', '/profilePictures/rcastillo1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('rcastillo@ieti.site', '/profilePictures/rcastillo2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('cvega@ieti.site', '/profilePictures/cvega1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('cvega@ieti.site', '/profilePictures/cvega2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('dreyes@ieti.site', '/profilePictures/dreyes1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('dreyes@ieti.site', '/profilePictures/dreyes2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('mgomez@ieti.site', '/profilePictures/mgomez1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('mgomez@ieti.site', '/profilePictures/mgomez2.jpg');

INSERT INTO pictures (email_user, path) VALUES
('msanchez@ieti.site', '/profilePictures/msanchez1.jpg');

INSERT INTO pictures (email_user, path) VALUES
('msanchez@ieti.site', '/profilePictures/msanchez2.jpg');

INSERT INTO messages (id_user, id_receptor, message_user, date) VALUES
('jgarcia@ieti.site', 'mmartinez@ieti.site', 'Hi, I am Joan Garcia! How are you?', '2023-05-14 10:00:00');