# Nonrepudiation in Digital Systems

## What is Nonrepudiation?

Nonrepudiation ensures that someone cannot deny performing a specific action in a digital system. When events like sending messages or making payments occur, strong evidence is preserved so the involved party cannot claim "I didn't do that" later. This is achieved through encryption, logging, and other verification mechanisms.

## Why Important for Online Bookstores

In the online bookstore from this coursework, nonrepudiation is essential. If a customer buys a book and later claims "I never bought that," how can the seller prove otherwise? Conversely, if a seller claims "I never received that order," the customer suffers. The `bookstore_log.txt` file records timestamps, IP addresses, user agents, and added books, serving as basic proof of user actions.

## Technologies Beyond Logging

Beyond simple file logging, several technologies provide stronger nonrepudiation:

1. **Digital Signatures**: Using PKI public-key cryptography, users sign transactions. This proves both identity and data integrity.

2. **Audit Trails**: Systems that log every change with user attribution, stored in tamper-resistant databases.

3. **Blockchain**: Cryptographically linked transactions that make retroactive changes impossible.

4. **Timestamp Authorities**: Trusted third parties that certify when documents existed in a specific state.

5. **Hash Chains**: Each log entry contains hashes of previous entries, making any alteration detectable.

For online bookstores, combining server logs with digital signatures on purchase confirmations protects merchants from fraudulent refunds and customers from unauthorized charges.
