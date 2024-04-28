describe('login screen test', () => {

  const url = "http://localhost:3000";

  it('test login without type email and password', () => {
    cy.login('', '');
  })

  it('test login with wrong email and wrong password', () => {
    cy.login('12345@gmail.com', '12345');

    cy.contains('Nenhum registro foi encontrado.');
    cy.get('.alert-danger').should('exist');
  })

  it('test login with correct data', () => {
    cy.login('wjunior_msn@hotmail.com', '12345');

    cy.get('.alert-danger').should('not.exist');
    cy.get('.navbar-brand').should('exist');
    cy.contains('Aniversariantes do Mês');
  })
})