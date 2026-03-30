-- Données de test pour script.sql
-- Ordre d'insertion: Utilisateur -> Categorie -> Article -> Article_photo -> Auteur

INSERT INTO Utilisateur (Id_Utilisateur, nom, prenom, identifiant, mdp) VALUES
(1, 'Rakoto', 'Lina', 'lina.rakoto', 'test123'),
(2, 'Andriam', 'Tiana', 'tiana.andriam', 'test123'),
(3, 'Rasoanaivo', 'Mickael', 'mickael.raso', 'test123'),
(4, 'Rabe', 'Fara', 'fara.rabe', 'test123');

INSERT INTO Categorie (Id_Categorie, rubrique) VALUES
(1, 'Politique'),
(2, 'Economie'),
(3, 'Societe'),
(4, 'Sport'),
(5, 'Culture');

INSERT INTO Article (Id_Article, titre, contenu, date_publication, Id_Categorie) VALUES
(1, '<h1 class="ng-star-inserted"><strong class="ng-star-inserted"><span class="ng-star-inserted">Guerre en Iran : Trump menace de &laquo; couper l&rsquo;eau &raquo; dans tout le Moyen-Orient, les g&eacute;ographes perplexes</span></strong></h1>', '<p><strong class="ng-star-inserted"><span class="ng-star-inserted"><img src="https://placehold.co/800x400/ff8c00/ffffff?text=Illustration+:+Trump+fermant+le+robinet+du+Moyen-Orient" alt="Donald Trump mena&ccedil;ant de couper l''eau au Moyen-Orient - Article parodique" width="484" height="242"></span></strong></p>
<p class="ng-star-inserted"><strong class="ng-star-inserted"><span class="ng-star-inserted">WASHINGTON D.C.</span></strong><span class="ng-star-inserted"> &mdash; Alors que les tensions s''intensifient autour du golfe Persique, Donald Trump a franchi une nouvelle &eacute;tape dans l''escalade rh&eacute;torique ce mardi. Lors d&rsquo;une conf&eacute;rence de presse improvis&eacute;e depuis le Bureau Ovale, le pr&eacute;sident a menac&eacute; de &laquo; fermer le grand robinet &raquo; du Moyen-Orient si l''Iran ne capitulait pas dans les 48 heures.</span></p>
<p class="ng-star-inserted"><span class="ng-star-inserted">&laquo; C''est tr&egrave;s simple. J''ai parl&eacute; avec mes g&eacute;n&eacute;raux, ce sont des gens fantastiques, tr&egrave;s intelligents. Je leur ai dit : "Pourquoi on ne coupe pas juste l''eau ?" &raquo;, a d&eacute;clar&eacute; M. Trump devant un parterre de journalistes m&eacute;dus&eacute;s. &laquo; Nous allons construire un barrage. Un barrage immense, magnifique. Le plus beau barrage que le monde ait jamais vu. Et croyez-moi, c''est l''Iran qui va le payer. &raquo;</span></p>
<p class="ng-star-inserted"><span class="ng-star-inserted">Interrog&eacute; par un journaliste de CNN sur la faisabilit&eacute; technique de priver d&rsquo;eau une r&eacute;gion enti&egrave;re de 7 millions de kilom&egrave;tres carr&eacute;s qui d&eacute;pend de multiples fleuves de sources diff&eacute;rentes, d&rsquo;usines de dessalement et de nappes phr&eacute;atiques, Donald Trump a balay&eacute; la question d''un revers de la main, la qualifiant de &laquo; Fake News g&eacute;ographique &raquo;.</span></p>
<p class="ng-star-inserted"><span class="ng-star-inserted">&laquo; Vous pensez toujours petit. Nous avons les meilleurs plombiers d&rsquo;Am&eacute;rique sur le coup, des gars de New York, des vrais durs. Ils vont trouver la vanne principale du Moyen-Orient, et ils vont la tourner. Clac ! Plus d''eau. Plus rien pour leurs chameaux, plus rien pour leurs th&eacute;s bizarres. &raquo;</span></p>
<p class="ng-star-inserted"><span class="ng-star-inserted">Le pr&eacute;sident a &eacute;galement sugg&eacute;r&eacute; qu''il pourrait d&eacute;ployer la &laquo; Space Force &raquo; pour aspirer les nuages au-dessus de T&eacute;h&eacute;ran &agrave; l''aide d''un &laquo; aspirateur spatial r&eacute;volutionnaire &raquo; et les rel&acirc;cher au-dessus des terrains de golf de la Trump Organization &agrave; Duba&iuml;, &laquo; l&agrave; o&ugrave; les gens savent faire des affaires &raquo;.</span></p>
<p class="ng-star-inserted"><span class="ng-star-inserted"><strong class="ng-star-inserted">DOCUMENT FUIT&Eacute; : Tableau pr&eacute;visionnel de l''Op&eacute;ration "Desert Dry"</strong></span></p>
<table style="border-collapse: collapse; width: 100%; height: 239.6px;" border="1"><colgroup><col style="width: 33.2822%;"><col style="width: 33.2822%;"><col style="width: 33.2822%;"></colgroup>
<tbody>
<tr style="height: 58.6px;">
<td><span class="ng-star-inserted">Zone / Sujet impact&eacute;</span></td>
<td><span class="ng-star-inserted">Cons&eacute;quence estim&eacute;e par le Pentagone</span></td>
<td><span class="ng-star-inserted">Solution de rechange propos&eacute;e par D. Trump</span></td>
</tr>
<tr style="height: 36.2px;">
<td><strong class="ng-star-inserted"><span class="ng-star-inserted">Oasis et fleuves iraniens</span></strong></td>
<td><span class="ng-star-inserted">Ass&egrave;chement total en 48 heures.</span></td>
<td><span class="ng-star-inserted">&laquo; Qu''ils boivent du Diet Coke. C''est rafra&icirc;chissant et c''est bon pour la ligne. &raquo;</span></td>
</tr>
<tr style="height: 36.2px;">
<td><strong class="ng-star-inserted"><span class="ng-star-inserted">Golfe Persique</span></strong></td>
<td><span class="ng-star-inserted">Disparition de la mer, flottes militaires &eacute;chou&eacute;es.</span></td>
<td><span class="router-outlet-wrapper ng-tns-c1959715094-0"><span class="ng-star-inserted">Construction d''un immense parking g&eacute;ant et du </span><span class="ng-star-inserted" style="font-style: italic;"><span class="ng-star-inserted">Trump Sand Casino &amp; Resort</span></span><span class="ng-star-inserted">.</span></span></td>
</tr>
<tr style="height: 36.2px;">
<td><strong class="ng-star-inserted"><span class="ng-star-inserted">Chameaux et dromadaires</span></strong></td>
<td><span class="ng-star-inserted">Soif extr&ecirc;me, ch&ocirc;mage technique des caravanes.</span></td>
<td><span class="ng-star-inserted">Remplacement obligatoire par des pick-up Ford F-150 (fabriqu&eacute;s aux &Eacute;tats-Unis).</span></td>
</tr>
<tr style="height: 36.2px;">
<td><strong class="ng-star-inserted"><span class="ng-star-inserted">Pays alli&eacute;s (Duba&iuml;, Riyad)</span></strong></td>
<td><span class="ng-star-inserted">Dommages collat&eacute;raux, p&eacute;nurie d''eau locale.</span></td>
<td><span class="router-outlet-wrapper ng-tns-c1959715094-0"><span class="ng-star-inserted">Livraison par drones de bouteilles </span><span class="ng-star-inserted" style="font-style: italic;"><span class="ng-star-inserted">Trump Ice Water</span></span><span class="ng-star-inserted"> au tarif amical de 45$ l''unit&eacute;.</span></span></td>
</tr>
<tr style="height: 36.2px;">
<td><strong class="ng-star-inserted"><span class="ng-star-inserted">Niveau des oc&eacute;ans du globe</span></strong></td>
<td><span class="ng-star-inserted">Baisse mondiale du niveau marin de 5 m&egrave;tres.</span></td>
<td><span class="ng-star-inserted">R&eacute;solution instantan&eacute;e du r&eacute;chauffement climatique. Mar-a-Lago est sauv&eacute; des inondations.</span></td>
</tr>
</tbody>
</table>
<p class="ng-star-inserted"><strong class="ng-star-inserted"><span class="ng-star-inserted">Une communaut&eacute; internationale constern&eacute;e (et confuse)</span></strong></p>
<p class="ng-star-inserted"><span class="ng-star-inserted">La d&eacute;claration a provoqu&eacute; une onde de choc, suivie d''une profonde confusion, dans les capitales mondiales. L''ONU a convoqu&eacute; une r&eacute;union d''urgence de son Conseil de S&eacute;curit&eacute; pour d&eacute;battre d''une r&eacute;solution interdisant &laquo; la fermeture des vannes continentales imaginaires &raquo;.</span></p>
<p class="ng-star-inserted"><span class="ng-star-inserted">De son c&ocirc;t&eacute;, le ministre iranien des Affaires &eacute;trang&egrave;res a r&eacute;agi sur le r&eacute;seau X (ex-Twitter) : &laquo; Nous condamnons fermement cette menace imp&eacute;rialiste, bien que nous n''ayons absolument aucune id&eacute;e de comment ils comptent s''y prendre techniquement. &raquo;</span></p>
<p class="ng-star-inserted"><span class="ng-star-inserted">En France, le pr&eacute;sident a appel&eacute; &agrave; la &laquo; d&eacute;sescalade hydrographique &raquo;, tandis qu''un collectif d''hydrologues a publi&eacute; une tribune dans le magazine </span><span class="ng-star-inserted" style="font-style: italic;"><span class="ng-star-inserted">Science</span></span><span class="ng-star-inserted"> rappelant qu''on ne peut pas &laquo; d&eacute;brancher la mer Caspienne avec une cl&eacute; &agrave; molette g&eacute;ante &raquo;.</span></p>
<p class="ng-star-inserted"><span class="ng-star-inserted">Aux &Eacute;tats-Unis, la strat&eacute;gie semble pourtant porter ses fruits sur le plan politique. Un sondage express r&eacute;alis&eacute; par Fox News r&eacute;v&egrave;le que 62 % des &eacute;lecteurs r&eacute;publicains se disent favorables &agrave; l&rsquo;id&eacute;e de &laquo; trouver la grosse poign&eacute;e rouge sous l''Irak et de la tourner &agrave; fond vers la droite &raquo;.</span></p>', '2026-03-30 05:00:00', 1);

INSERT INTO Article_photo (Id_Article_photo, chemin, alt, Id_Article) VALUES
(1, '/uploads/articles/image-1.jpg', 'Affiche des elections locales 2026', 1);
INSERT INTO Auteur (Id_Utilisateur, Id_Article) VALUES
(1, 1);


INSERT INTO Article (Id_Article, titre, contenu, date_publication, Id_Categorie) VALUES
(2, '<h1><strong>L''Iran dévoile son projet de &laquo; dôme anti-sécheresse &raquo; face aux menaces américaines</strong></h1>', '<p><strong>TÉHÉRAN</strong> &mdash; Suite aux déclarations du président américain concernant une potentielle &laquo; coupure d''eau &raquo; au Moyen-Orient, l''Iran n''a pas tardé à réagir. Le gouvernement a annoncé ce matin la mise en chantier d''un gigantesque parapluie inversé visant à récolter la rosée matinale.</p><p>&laquo; Si l''Amérique veut jouer avec nos robinets, nous jouerons avec l''atmosphère &raquo;, a déclaré un porte-parole. Le projet, ironiquement baptisé &laquo; Opération Oasis &raquo;, suscite l''admiration ou la perplexité de la communauté internationale.</p>', '2026-03-31 10:00:00', 1),
(3, '<h1><strong>La crise iranienne fait trembler les marchés : Ruée mondiale sur l''eau minérale et les chameaux d''occasion</strong></h1>', '<p><strong>LONDRES</strong> &mdash; La Bourse dévisse ce matin. Les menaces d''assèchement total de la région ont provoqué un vent de panique insolite sur les marchés financiers. L''action de grandes marques d''eau en bouteille a bondi de 400 % en l''espace de quelques heures.</p><p>Parallèlement, la valeur du baril de pétrole est tombée à son plus bas historique, les investisseurs estimant que &laquo; de toute façon on ne peut pas boire du pétrole &raquo;. Des concessionnaires à Dubaï signalent une pénurie inédite de chameaux hybrides d''occasion.</p>', '2026-03-31 14:30:00', 2),
(4, '<h1><strong>Escalade en Iran : L''ONU plaide pour l''envoi massif de pistolets à eau en guise de maintien de la paix</strong></h1>', '<p><strong>GENÈVE</strong> &mdash; Face à ce qui est désormais qualifié de &laquo; première guerre hydro-rhétorique &raquo;, le Conseil de Sécurité de l''ONU tente de calmer le jeu. Une résolution inattendue propose d''armer les casques bleus exclusivement de pistolets à eau en plastique recyclé.</p><p>&laquo; C''est un symbole fort, et cela hydratera les troupes &raquo;, a défendu le Secrétaire Général. La proposition a toutefois été bloquée par le veto américain, arguant que &laquo; les pistolets à eau ne sont pas fabriqués aux États-Unis &raquo;.</p>', '2026-04-01 09:15:00', 1);

INSERT INTO Article_photo (Id_Article_photo, chemin, alt, Id_Article) VALUES
(2, '/uploads/articles/image-2.jpg', 'Illustration du dôme iranien', 2),
(3, '/uploads/articles/image-3.jpg', 'Trader paniqué avec une bouteille d''eau', 3),
(4, '/uploads/articles/image-4.jpg', 'Casques bleus avec pistolets à eau', 4);

INSERT INTO Auteur (Id_Utilisateur, Id_Article) VALUES
(2, 2),
(3, 3),
(4, 4);

-- Si besoin, remettre les sequences en phase apres insertion manuelle des IDs
SELECT setval('utilisateur_id_utilisateur_seq', (SELECT MAX(Id_Utilisateur) FROM Utilisateur));
SELECT setval('categorie_id_categorie_seq', (SELECT MAX(Id_Categorie) FROM Categorie));
SELECT setval('article_id_article_seq', (SELECT MAX(Id_Article) FROM Article));
SELECT setval('article_photo_id_article_photo_seq', (SELECT MAX(Id_Article_photo) FROM Article_photo));
