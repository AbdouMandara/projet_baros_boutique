# Directive : Initialisation de l'Architecture

## Objectif
Maintenir une séparation claire entre les instructions (Directives), la prise de décision (Orchestration/IA) et l'exécution technique (Scripts).

## Structure
- **/directives/** : Dossier contenant les fichiers Markdown décrivant les procédures (SOP).
- **/execution/** : Dossier contenant les scripts Python déterministes.
- **/.tmp/** : Dossier pour les fichiers de travail temporaires.

## Principes de fonctionnement
1. **Consulter d'abord** : Avant de créer un script, vérifier si un outil similaire existe dans `execution/`.
2. **Auto-correction (Self-anneal)** : Si un script échoue, analyser l'erreur, corriger le script et mettre à jour la directive correspondante.
3. **Mise à jour continue** : Les directives sont vivantes. Enrichissez-les au fur et à mesure des découvertes techniques.

## Procédures standard
- Toute nouvelle tâche complexe doit commencer par la création ou la lecture d'une directive dans `directives/`.
- Tout traitement de données lourd ou répétitif doit être confié à un script dans `execution/`.
