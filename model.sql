CREATE TABLE listings (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INTEGER NOT NULL,
    title VARCHAR(255) NOT NULL,
    description LONGTEXT,
    salary VARCHAR(45),
    tags VARCHAR(255),
    company VARCHAR(255),
    address VARCHAR(255),
    city VARCHAR(255),
    state VARCHAR(255),
    phone VARCHAR(45),
    email VARCHAR(45),
    requirements LONGTEXT,
    benefits LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_listings_users FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE users (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    city VARCHAR(255),
    state VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO listings (title, description, 
  salary, tags, company, address, 
  city, state, phone, email, 
  requirements, benefits, user_id)
  VALUES (
    'Senior Software Engineer', 
    'We are seeking a skilled software engineer to develop high-quality software solutions', 
    ' 90000', 'development, coding, java, python', 
    'Tech Solutions Inc.', '123 Main St', 
    'Albany', 'NY', '348-334-3949',
    'info@techsolutions.com', 'Bachelors degree in Computer Science or related field, 3+ years of software development experience',
    'Healthcare, 401(k) matching, flexible work hours', 1
);

INSERT INTO listings (title, description, 
  salary, tags, company, address, 
  city, state, phone, email, 
  requirements, benefits, user_id)
  VALUES (
    'Marketing Specialist', 
    'We are looking for a Marketing Specialist to create and manage marketing campaigns', 
    ' 70000', 'marketing, advertising', 
    'Marketing Pros', '456 Market St', 
    'San Francisco', 'CA', '454-344-3344',
    'info@marketingpros.com', 'Bachelors degree in Marketing or related field, experience in digital marketing',
    'Health and dental insurance, paid time off, remote work options', 2
);

INSERT INTO listings (title, description, 
  salary, tags, company, address, 
  city, state, phone, email, 
  requirements, benefits, user_id)
  VALUES (
    'Web Developer', 
    'Join our team as a Web Developer and create amazing web applications', 
    ' 85000', 'web development, programming', 
    'WebTech Solutions', '789 Web Ave', 
    'Chicago', 'IL', '456-876-5432',
    'info@webtechsolutions.com', 'Bachelors degree in Computer Science or related field, proficiency in HTML, CSS, JavaScript',
    'Competitive salary, professional development opportunities, friendly work environment', 3
);

INSERT INTO listings (title, description, 
  salary, tags, company, address, 
  city, state, phone, email, 
  requirements, benefits, user_id)
  VALUES (
    'Data Analyst', 
    'We are hiring a Data Analyst to analyze and interpret data for insights', 
    ' 75000', 'data analysis, statistics', 
    'Data Insights LLC', '101 Data St', 
    'Chicago', 'IL', '444-555-5555',
    'info@datainsights.com', 'Bachelors degree in Data Science or related field, strong analytical skills',
    'Health benefits, remote work options, casual dress code', 1
);

INSERT INTO listings (title, description, 
  salary, tags, company, address, 
  city, state, phone, email, 
  requirements, benefits, user_id)
  VALUES (
    'Graphic Designer', 
    'Join our creative team as a Graphic Designer and bring ideas to life', 
    ' 70000', 'graphic design, creative', 
    'CreativeWorks Inc.', '234 Design Blvd', 
    'Albany', 'NY', '499-321-9876',
    'info@creativeworks.com', 'Bachelors degree in Graphic Design or related field, proficiency in Adobe Creative Suite',
    'Flexible work hours, creative work environment, opportunities for growth', 2
);

INSERT INTO users (name, email, password, city, state)
  VALUES 
  ('John Doe', 'user1@gmail.com', 'john pass', 'Albany', 'NY');

INSERT INTO users (name, email, password, city, state)
  VALUES 
  ('Jane Doe', 'jane.doe@gmail', 'janepass', 'washington', 'NY');

INSERT INTO users (name, email, password, city, state)
  VALUES 
  ('Steve smith', 'steve.smith@gmail', 'stevepass', 'Washington', 'DC');
