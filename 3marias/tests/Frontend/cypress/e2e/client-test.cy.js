describe('client screen test', () => {
    it('test create client with required fields', () => {
      cy.doLogin();
      
      // Clients List Screen
      cy.visit("http://localhost:3000/admin/clients");
      cy.get('[data-tooltip-id="btnAdd"]').should('exist');
      cy.get('[data-tooltip-id="btnAdd"]').click();

      // Clients Form Screen
      cy.get('#nameInput').type('Raimundo Gonçalves');
      cy.get('#cpfInput').type('346.305.462-00');
      cy.get('[type="submit"]').click();
      cy.wait(30000);

      // Clients Form Screen Asserts
      cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
      cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
      cy.get('.alert-danger').should('not.exist');
    })

    it('test create client with required fields + short rg', () => {
        cy.doLogin();
        
        // Clients List Screen
        cy.visit("http://localhost:3000/admin/clients");
        cy.get('[data-tooltip-id="btnAdd"]').should('exist');
        cy.get('[data-tooltip-id="btnAdd"]').click();
  
        // Clients Form Screen
        cy.get('#nameInput').type('Raimundo Gonçalves');
        cy.get('#cpfInput').type('346.305.462-00');
        cy.get('#rgInput').type('000');
        cy.get('[type="submit"]').click();
        cy.wait(30000);
  
        // Clients Form Screen Asserts
        cy.get('.alert-danger').should('exist');
      })
  })