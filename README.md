# Szakdolgozat 2025

## A projekt telepítéséhez és futtatásához az alábbi lépéseket kell követni:

### PHP verzió és XAMPP/WAMP szerver telepítése
- A legfrissebb PHP verzióra lesz szükség, amit egy XAMPP vagy WAMP szerver biztosít. A XAMPP automatikusan a legfrissebb PHP verzióval telepíti a környezetet.
- Indítsuk el az Apache és MySQL szervereket a XAMPP vezérlőpultján.

### Composer telepítése
- A Composer szükséges a projekt függőségeinek kezeléséhez. A Composer legfrissebb verzióját a következő linken töltheted le: https://getcomposer.org/.

### Projekt klónozása
- Klónozzuk a projektet a GitHubról a következő parancs használatával: git clone <repo_url>

### Függőségek telepítése
- A projekt mappájában futtassuk az alábbi parancsokat a szükséges PHP és JavaScript függőségek telepítéséhez:
  - composer install
  - npm install

### Stílusok és JS fájlok generálása
- A következő parancsokkal generáljunk le minden szükséges CSS és JavaScript fájlt:
  - npm run build

### Fejlesztői környezet indítása
- Miután minden telepítés és generálás sikeresen megtörtént, indítsuk el a fejlesztői szervert a következő parancs futtatásával:
  - composer run dev

### Webalkalmazás elérhetősége
- A projekt elérhető lesz a http://localhost:8000 címen.
