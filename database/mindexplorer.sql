-- Apaga o banco de dados caso exista.
DROP DATABASE IF EXISTS mindexplorer;

-- Cria o banco de dados
CREATE DATABASE mindexplorer CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Seleciona banco de dados.
USE mindexplorer; 

-- Cria tabela de usuários/autores.
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(255),
    user_email VARCHAR(255),
    user_birth DATE,
    user_photo VARCHAR(255),
    user_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_profile TEXT,
    user_password VARCHAR(255),
    user_type ENUM('user', 'author', 'admin') DEFAULT 'user',
    user_status ENUM('on', 'off', 'deleted') DEFAULT 'on'
);

-- Cria tabela de artigos
CREATE TABLE articles (
    art_id INT PRIMARY KEY AUTO_INCREMENT,
    art_title VARCHAR(127),
    art_intro VARCHAR(255),
    art_thumb VARCHAR(255),
    art_content TEXT,
    art_author INT,
    art_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    art_views INT DEFAULT '0',
    art_status ENUM('on', 'off', 'deleted') DEFAULT 'on',
    FOREIGN KEY (art_author) REFERENCES users (user_id)
);

-- Cria tabela de comentários nos artigos
CREATE TABLE comments (
    cmt_id INT PRIMARY KEY AUTO_INCREMENT,
    cmt_article INT,
    cmt_author INT,
    cmt_comment TEXT,
    cmt_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cmt_status ENUM('on', 'off', 'deleted') DEFAULT 'on',
    FOREIGN KEY (cmt_article) REFERENCES articles (art_id),
    FOREIGN KEY (cmt_author) REFERENCES users (user_id)
);

-- Cria tabela com contatos do site
CREATE TABLE contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(127),
    email VARCHAR(255),
    subject VARCHAR(255),
    message TEXT,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('on', 'off', 'deleted') DEFAULT 'on'
);
CREATE TABLE about(
    about_id INT PRIMARY KEY AUTO_INCREMENT,
    about_name VARCHAR(255),
    about_email VARCHAR(255),
    about_birthday date,
    about_cell varchar(15),
    about_specialties varchar(255)

);
-- criar tabela sobre
INSERT INTO about(
    about_name,
    about_email,
    about_birthday,
    about_cell,
    about_specialties
)VALUES
(
    'Natan dos Santos Coelho',
    'Natancoelho@gmail.com',
    '23/07/2001',
    '(21) 99768-0067',
    'Programador, estudante de Pscicologia'
),
(
    'Laura Tchaly Manes dos Santos',
    'lauratchaly@gmail.com',
    '20/10/2003',
    '(21) 99213-5211',
    'programadora, estudante de psicologia'
),
(
    'Sandro Cesar Dantas Pereira',
    'sandropereira@gmail,com',
    '30/09/1991',
    '(21) 99903-5770',
    'Programador, produtor musical, estudante de psicologia'
);

-- Insere dados em 'users'
INSERT INTO users (
    user_name,
    user_email,
    user_birth,
    user_photo,
    user_profile,
    user_password,
    user_type
) VALUES 
(
    'Natan dos Santos coelho',
    'natancoelho@gmail.com',
    '2001-07-23',
    '/usuários_img/6.jpeg',
    'Progamador, estudante, amante do conhecimento da mente humana.',
    SHA1('Qw3rtyui0P'),
    'admin'
),
(
    'Laura Tchaly Manes dos Santos',
    'lauratchaly@gmail.com',
    '2003-10-20',
    '/usuários_img/1.jpeg',
    'Progamadora, estudade de psicologia e do conhecimento humano.',
    SHA1('Qw3rtyui0P'),
    'admin'
),
(
    'Sandro Cesar Dantas Pereira',
    'sandropereira@gmail.com',
    '1991-09-30',
    '/usuários_img/2.jpeg',
    'Progamador, estudante, produtor musical, e entusiasta do desenvolvimento humano.',
    SHA1('Qw3rtyui0P'),
    'admin'
),
(
    'Laura Tchaly Manes dos Santos',
    'lauratchaly@gmail.com',
    '2003-10-20',
    '/usuários_img/1.jpeg',
    'Progamadora, estudade de psicologia e do conhecimento humano.',
    SHA1('Qw3rtyui0P'),
    'admin'
),
(
    'Sandro Cesar Dantas Pereira',
    'sandropereira@gmail.com',
    '1991-09-30',
    '/usuários_img/2.jpeg',
    'Progamador, estudante, produtor musical, e entusiasta do desenvolvimento humano.',
    SHA1('Qw3rtyui0P'),
    'admin'
);

-- Insere dados em 'articles'
INSERT INTO articles (
    art_title,
    art_intro,
    art_thumb,
    art_content,
    art_author
) VALUES
(
    'Autismo',
    'O que é?, Vamos entender sobre!',
    '/img/articles/altismo.JPG',
    '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto fugiat, nesciunt iure porro aliquid id consequuntur nisi placeat assumenda 
    vero magni repellendus possimus corporis sed, quaerat totam? Veniam, pariatur ex?</p><p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Inventore sunt quo nam commodi repudiandae voluptas excepturi est aut iste veniam. Itaque possimus ullam eius quaerat? Quam aliquam eius corporis ut!</p><img src="/img/articles/altismo.JPG" ><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad quisquam amet, sunt, magnam debitis aliquam voluptates eius veniam explicabo molestias enim illo quam exercitationem ducimus vel eos eligendi aut libero.</p><ul><li><a href="https://github.com/Natezer4">GitHub do Natan</a></li><li><a href="https://www.instagram.com/jovem.nate/">Instagram do Natan</a></li><li><a href="https://www.linkedin.com/in/natan-dos-santos-coelho-81122220a/">linkedin do Natan</a></li></ul><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. At itaque deserunt perferendis sit voluptatum sunt, minus labore ratione modi nesciunt distinctio temporibus amet omnis sapiente, dicta repudiandae ipsum, eaque deleniti!</p><p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptas quos quasi quis pariatur iure officia ab, eius beatae, fuga, in dolores neque possimus necessitatibus nostrum nulla expedita tempore harum tenetur?</p>',
    '1'
),
(
    'Ansiedade',
    'A doença do século',
    '/img/articles/ansiedade.JPG',
    '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto fugiat, nesciunt iure porro aliquid id consequuntur nisi placeat assumenda vero magni repellendus possimus corporis sed, quaerat totam? Veniam, pariatur ex?</p><p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Inventore sunt quo nam commodi repudiandae voluptas excepturi est aut iste veniam. Itaque possimus ullam eius quaerat? Quam aliquam eius corporis ut!</p><img src="/img/articles/ansiedade.JPG" ><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad quisquam amet, sunt, magnam debitis aliquam voluptates eius veniam explicabo molestias enim illo quam exercitationem ducimus vel eos eligendi aut libero.</p><ul><li><a href="https://github.com/Natezer4">GitHub do Natan</a></li><li><a href="https://www.instagram.com/jovem.nate/">Instagram do Natan</a></li><li><a href="https://www.linkedin.com/in/natan-dos-santos-coelho-81122220a/">linkedin do Natan</a></li></ul><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. At itaque deserunt perferendis sit voluptatum sunt, minus labore ratione modi nesciunt distinctio temporibus amet omnis sapiente, dicta repudiandae ipsum, eaque deleniti!</p><p>Lorem, ipsum dolor sit amet consectetur
     adipisicing elit. Voluptas quos quasi quis pariaturiure officia ab, eius beatae, fuga, in dolores neque possimus necessitatibus nostrum nulla expedita tempore harum tenetur?</p>',
    '1'
),
(
    'Psicologia infantil',
    'Como a psicologia ajuda a entender a mente infantil?',
    '/img/articles/crianca.JPG',
    '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto fugiat, nesciunt iure porro aliquid id consequuntur nisi placeat assumenda vero magni repellendus possimus corporis sed, quaerat totam? Veniam, pariatur ex?</p><p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Inventore sunt quo nam commodi repudiandae voluptas excepturi est aut iste veniam. Itaque possimus ullam eius quaerat? Quam aliquam eius corporis ut!</p><img src="/img/articles/crianca.JPG" ><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad quisquam amet, sunt, magnam debitis aliquam voluptates eius veniam explicabo molestias enim illo quam exercitationem ducimus vel eos eligendi aut libero.</p><ul><li><a href="https://github.com/lauraTchaly">GitHub da Laura</a></li><li><a href="https://www.instagram.com/lauratchaly/">Instagram da Laura</a></li><li><a href="https://www.linkedin.com/in/laura-tchaly-manes-dos-santos-6ba591231/">Linkedin da Laura</a></li></ul><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. At itaque deserunt perferendis sit voluptatum sunt, minus labore ratione modi nesciunt distinctio temporibus amet omnis sapiente, dicta repudiandae ipsum, eaque deleniti!</p><p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptas quos 
    quasi quis pariatur iure officia ab, eius beatae, fuga, in dolores neque possimus necessitatibus nostrum nulla expedita tempore harum tenetur?</p>',
    '1'
);

-- Insere dados em 'articles'
INSERT INTO articles (
    art_title,
    art_intro,
    art_thumb,
    art_content,
    art_author
) VALUES
(
    'Síndrome de Down',
    'O que é, características e como identificar. ',
    '/img/articles/sindromededown.JPG',
    '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto fugiat, nesciunt iure porro aliquid id consequuntur nisi placeat assumenda vero magni repellendus possimus corporis sed, quaerat totam? Veniam, pariatur ex?</p><p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Inventore sunt quo nam commodi repudiandae voluptas excepturi est aut iste veniam. Itaque possimus ullam eius quaerat? Quam aliquam eius corporis ut!</p><img src="/img/articles/sindromededown.JPG" ><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad quisquam amet, sunt, magnam debitis aliquam voluptates eius veniam explicabo molestias enim illo quam exercitationem ducimus vel eos eligendi aut libero.</p><ul><li><a href="https://github.com/spereira91">GitHub do Sandro</a></li><li><a href="https://www.instagram.com/spereira9/">Instagram do Sandro</a></li><li><a href="https://www.linkedin.com/in/sandro-pereira-b36655109/">Linkedin do Sandro</a></li></ul><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. At itaque deserunt perferendis sit voluptatum sunt, minus labore ratione modi nesciunt distinctio temporibus amet omnis sapiente, dicta repudiandae ipsum, eaque deleniti!</p><p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptas quos quasi quis pariatur iure officia ab, 
    eius beatae, fuga, in dolores neque possimus necessitatibus nostrum nulla expedita tempore harum tenetur?</p>',
    '4'
),
(
    'Depressão',
    'Por que tantas pessoas sofrem com esse mal?',
    '/img/articles/transtornos.JPG',
    '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto fugiat, nesciunt iure porro aliquid id consequuntur nisi placeat assumenda vero magni repellendus possimus corporis sed, quaerat totam? Veniam, pariatur ex?</p><p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Inventore sunt quo nam commodi repudiandae voluptas excepturi est aut iste veniam. Itaque possimus ullam eius quaerat? Quam aliquam eius corporis ut!</p><img src="/img/articles/transtornos.JPG" ><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad quisquam amet, sunt, magnam debitis aliquam voluptates eius veniam explicabo molestias enim illo quam exercitationem ducimus vel eos eligendi aut libero.</p><ul><li><a href="https://github.com/lauraTchaly">GitHub da Laura</a></li><li><a href="https://www.instagram.com/lauratchaly/">Instagram da Laura</a></li><li><a href="https://www.linkedin.com/in/laura-tchaly-manes-dos-santos-6ba591231/">Linkedin da Laura</a></li></ul><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. At itaque deserunt perferendis sit voluptatum sunt, minus labore ratione modi nesciunt distinctio temporibus amet omnis sapiente, dicta repudiandae ipsum, eaque deleniti!</p><p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptas quos
     quasi quis pariatur iure officia ab, eius beatae, fuga, in dolores neque possimus necessitatibus nostrum nulla expedita tempore harum tenetur?</p>',
    '5'
),
