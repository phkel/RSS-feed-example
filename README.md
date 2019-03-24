## Rakenduse käivitamiseks

1. Config.php failis muuta andmebaasi ligipääsud.

2. Luua tabel andmebaasis (sql päring)
```sql
CREATE TABLE article (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(300),
  link VARCHAR(300),
  description VARCHAR(500),
  pubDate VARCHAR(300) 
)
```

RSS-kanali parseriks on kasutatud antud teeki https://github.com/dg/rss-php 
